/**
 * SIGMA-3 WhatsApp Automation Sidecar (Baileys Engine)
 * MTsN 3 Tulungagung
 *
 * Microservice wrapping @whiskeysockets/baileys (pure WebSocket, 0% Chrome) to handle:
 * 1. 24/7 CS AI SIGMA-3 Matsanega Bot for Wali Murid
 * 2. Incoming message forwarding to Laravel Webhook (/api/whatsapp-webhook)
 * 3. Outbound messaging via REST API for Laravel notification services
 * 4. QR pairing via web (/admin/whatsapp/qr) and terminal ASCII
 */

const express = require('express');
const qrcode = require('qrcode');
const path = require('path');
const fs = require('fs');
const http = require('http');
const https = require('https');
const pino = require('pino');
const {
  default: makeWASocket,
  useMultiFileAuthState,
  DisconnectReason,
  fetchLatestBaileysVersion,
  makeCacheableSignalKeyStore,
} = require('@whiskeysockets/baileys');
const { Boom } = require('@hapi/boom');
const { discoverPersistedSessions } = require('./session-store');

// -----------------------------------------------------------------------------
// Auto-load .env (support current dir or parent Laravel .env)
// -----------------------------------------------------------------------------
function loadEnv() {
  const envCandidates = [
    path.join(__dirname, '.env'),
    path.join(__dirname, '..', '.env'),
  ];
  for (const envPath of envCandidates) {
    if (fs.existsSync(envPath)) {
      try {
        const content = fs.readFileSync(envPath, 'utf8');
        content.split(/\r?\n/).forEach((line) => {
          const trimmed = line.trim();
          if (!trimmed || trimmed.startsWith('#')) return;
          const eq = trimmed.indexOf('=');
          if (eq > 0) {
            const key = trimmed.slice(0, eq).trim();
            let val = trimmed.slice(eq + 1).trim();
            if (
              (val.startsWith('"') && val.endsWith('"')) ||
              (val.startsWith("'") && val.endsWith("'"))
            ) {
              val = val.slice(1, -1);
            }
            if (process.env[key] === undefined) {
              process.env[key] = val;
            }
          }
        });
      } catch (err) {
        console.warn(`[Env] Failed to read ${envPath}: ${err.message}`);
      }
    }
  }
}
loadEnv();

// -----------------------------------------------------------------------------
// Configuration
// -----------------------------------------------------------------------------
const PORT = parseInt(process.env.WHATSAPP_WEB_PORT || process.env.PORT || '3001', 10);
const HOST = process.env.WHATSAPP_WEB_HOST || process.env.HOST || '127.0.0.1';
const TOKEN = process.env.WHATSAPP_WEB_TOKEN || process.env.SIDECAR_TOKEN || '';
const SESSION_DIR = process.env.WHATSAPP_WEB_SESSION_DIR || process.env.SESSION_DIR || path.join(__dirname, 'sessions');
const AUTO_START_DEFAULT = true;

// Webhook configuration
const DEFAULT_WEBHOOK_URL = process.env.APP_URL
  ? `${process.env.APP_URL.replace(/\/+$/, '')}/api/whatsapp-webhook`
  : 'http://127.0.0.1/api/whatsapp-webhook';

const WEBHOOK_URL = process.env.WHATSAPP_WEBHOOK_URL || process.env.WEBHOOK_URL || DEFAULT_WEBHOOK_URL;
const WEBHOOK_SECRET = process.env.WHATSAPP_WEB_WEBHOOK_SECRET || process.env.WEBHOOK_SECRET || TOKEN || '';

if (!fs.existsSync(SESSION_DIR)) {
  fs.mkdirSync(SESSION_DIR, { recursive: true });
}

console.log('----------------------------------------------------');
console.log('⚡ SIGMA-3 WhatsApp Sidecar (Baileys Engine) starting...');
console.log(`📡 Host/Port      : ${HOST}:${PORT}`);
console.log(`🔗 Webhook URL    : ${WEBHOOK_URL}`);
console.log(`🔐 Webhook Secret : ${WEBHOOK_SECRET ? 'Configured (Hidden)' : 'None'}`);
console.log(`🔑 Bearer Token   : ${TOKEN ? 'Configured (Hidden)' : 'None'}`);
console.log(`📁 Session Dir    : ${SESSION_DIR}`);
console.log('----------------------------------------------------');

/** sessionId -> { id, sock, status, qrDataUri, subscribers: Set<res>, retryCount } */
const sessions = new Map();

// Pino silent logger to keep console clean and fast
const logger = pino({ level: process.env.LOG_LEVEL || 'silent' });

// -----------------------------------------------------------------------------
// Helper functions
// -----------------------------------------------------------------------------
function auth(req, res, next) {
  if (!TOKEN) return next();
  const header = req.headers.authorization || '';
  if (header !== `Bearer ${TOKEN}`) {
    return res.status(401).json({ error: 'unauthorized' });
  }
  next();
}

function getSession(sessionId) {
  const session = sessions.get(sessionId);
  if (!session) {
    const err = new Error(`session '${sessionId}' not found`);
    err.http = 404;
    throw err;
  }
  return session;
}

function requireReady(session) {
  if (session.status !== 'ready' || !session.sock) {
    const err = new Error(`session not ready (current status: ${session.status})`);
    err.http = 409;
    throw err;
  }
}

function broadcast(sessionId, event, data) {
  const session = sessions.get(sessionId);
  if (!session) return;
  const line = `event: ${event}\ndata: ${JSON.stringify({ sessionId, ...data })}\n\n`;
  for (const res of session.subscribers) {
    try {
      res.write(line);
    } catch (_) {
      /* subscriber gone */
    }
  }
}

/**
 * Normalizes input phone to Baileys JID:
 * 08123456789 -> 628123456789@s.whatsapp.net
 * 628123456789@c.us -> 628123456789@s.whatsapp.net
 */
function toBaileysJid(input) {
  if (!input || typeof input !== 'string') return '';
  if (input.endsWith('@g.us') || input.endsWith('@s.whatsapp.net')) {
    return input;
  }
  let digits = input.split('@')[0].replace(/\D+/g, '');
  if (!digits) return '';
  if (digits.startsWith('0')) {
    digits = '62' + digits.slice(1);
  }
  return `${digits}@s.whatsapp.net`;
}

/**
 * Normalizes Baileys JID to legacy WhatsApp Web format (e.g. 628xxx@c.us)
 * to keep 100% backward compatibility with Laravel SIGMA-3
 */
function toLegacyWaId(jid) {
  if (!jid || typeof jid !== 'string') return '';
  const digits = jid.split('@')[0].split(':')[0].replace(/\D+/g, '');
  return `${digits}@c.us`;
}

/**
 * Extract clean text message from Baileys message object
 */
function extractMessageBody(msg) {
  if (!msg) return '';
  return (
    msg.conversation ||
    msg.extendedTextMessage?.text ||
    msg.imageMessage?.caption ||
    msg.videoMessage?.caption ||
    msg.documentMessage?.caption ||
    msg.buttonsResponseMessage?.selectedDisplayText ||
    msg.listResponseMessage?.title ||
    ''
  );
}

/**
 * Fetch buffer from remote URL
 */
function fetchBuffer(url) {
  return new Promise((resolve, reject) => {
    const client = url.startsWith('https') ? https : http;
    client.get(url, (res) => {
      if (res.statusCode !== 200) {
        return reject(new Error(`Failed to download media: HTTP ${res.statusCode}`));
      }
      const chunks = [];
      res.on('data', (c) => chunks.push(c));
      res.on('end', () => resolve(Buffer.concat(chunks)));
      res.on('error', reject);
    }).on('error', reject);
  });
}

/**
 * Forward message to Laravel Webhook via HTTP POST
 */
async function forwardToWebhook(fromWaId, body, timestamp) {
  if (!WEBHOOK_URL) return;

  const payload = {
    event: 'message',
    data: {
      message: {
        from: fromWaId,
        body: body ?? '',
        fromMe: false,
        isStatus: false,
        type: 'chat',
        timestamp: timestamp || Math.floor(Date.now() / 1000),
      },
    },
  };

  const payloadString = JSON.stringify(payload);

  try {
    const urlObj = new URL(WEBHOOK_URL);
    const isHttps = urlObj.protocol === 'https:';
    const client = isHttps ? https : http;

    const options = {
      method: 'POST',
      hostname: urlObj.hostname,
      port: urlObj.port || (isHttps ? 443 : 80),
      path: urlObj.pathname + urlObj.search,
      headers: {
        'Content-Type': 'application/json',
        'Content-Length': Buffer.byteLength(payloadString),
        'Accept': 'application/json',
        'User-Agent': 'SIGMA3-WhatsApp-Sidecar-Baileys/2.0',
      },
      timeout: 10000,
    };

    if (WEBHOOK_SECRET) {
      options.headers['X-Webhook-Secret'] = WEBHOOK_SECRET;
    }

    const req = client.request(options, (res) => {
      let data = '';
      res.on('data', (chunk) => (data += chunk));
      res.on('end', () => {
        if (res.statusCode >= 200 && res.statusCode < 300) {
          console.log(`[Webhook] Message from ${fromWaId} forwarded successfully (HTTP ${res.statusCode})`);
        } else {
          console.warn(`[Webhook] Webhook returned HTTP ${res.statusCode}: ${data}`);
        }
      });
    });

    req.on('error', (err) => {
      console.error(`[Webhook] Request error: ${err.message}`);
    });

    req.on('timeout', () => {
      req.destroy();
      console.warn(`[Webhook] Request timeout forwarding message from ${fromWaId}`);
    });

    req.write(payloadString);
    req.end();
  } catch (err) {
    console.error(`[Webhook] Forwarding exception: ${err.message}`);
  }
}

/**
 * Helper: wait up to timeoutMs until QR is generated or session is ready
 */
function waitForQrOrReady(session, timeoutMs = 8000) {
  return new Promise((resolve) => {
    if (session.status === 'ready' || session.qrDataUri) {
      return resolve(session);
    }
    const timer = setTimeout(() => resolve(session), timeoutMs);
    const interval = setInterval(() => {
      if (session.status === 'ready' || session.qrDataUri || session.status === 'disconnected') {
        clearTimeout(timer);
        clearInterval(interval);
        resolve(session);
      }
    }, 250);
  });
}

// -----------------------------------------------------------------------------
// Boot Baileys Session
// -----------------------------------------------------------------------------
async function bootSession(sessionId, isRetry = false) {
  let session = sessions.get(sessionId);
  if (session && session.status === 'ready' && !isRetry) {
    return session;
  }

  const sessionPath = path.join(SESSION_DIR, `session-${sessionId}`);
  if (!fs.existsSync(sessionPath)) {
    fs.mkdirSync(sessionPath, { recursive: true });
  }

  // Clean old socket if re-booting
  if (session && session.sock) {
    try {
      session.sock.ev.removeAllListeners();
      session.sock.end(undefined);
    } catch (_) {}
  }

  if (!session) {
    session = {
      id: sessionId,
      sock: null,
      status: 'initializing',
      qrDataUri: null,
      subscribers: new Set(),
      retryCount: 0,
    };
    sessions.set(sessionId, session);
  } else {
    session.status = 'initializing';
    session.qrDataUri = null;
  }

  try {
    const { state, saveCreds } = await useMultiFileAuthState(sessionPath);
    const { version } = await fetchLatestBaileysVersion().catch(() => ({
      version: [2, 3000, 1015901307],
    }));

    console.log(`[Session:${sessionId}] Initializing Baileys WA Socket (v${version.join('.')})...`);

    const sock = makeWASocket({
      version,
      logger,
      printQRInTerminal: false,
      auth: {
        creds: state.creds,
        keys: makeCacheableSignalKeyStore(state.keys, logger),
      },
      generateHighQualityLinkPreview: false,
      syncFullHistory: false,
      markOnlineOnConnect: true,
      defaultQueryTimeoutMs: 60000,
      browser: ['Ubuntu', 'Chrome', '22.04.4'],
    });

    session.sock = sock;

    // Handle credential saves
    sock.ev.on('creds.update', saveCreds);

    // Handle connection updates (QR, connection open, close, reconnect)
    sock.ev.on('connection.update', async (update) => {
      const { connection, lastDisconnect, qr } = update;

      if (qr) {
        session.qrDataUri = await qrcode.toDataURL(qr);
        session.status = 'qr';

        console.log('\n======================================================');
        console.log(`[Session:${sessionId}] 📲 QR CODE GENERATED (Baileys Engine)!`);
        console.log(`Scan in WhatsApp > Linked Devices or open /admin/whatsapp/qr`);
        console.log('======================================================\n');

        try {
          const terminalQr = await qrcode.toString(qr, { type: 'terminal', small: true });
          console.log(terminalQr);
        } catch (_) {}

        broadcast(sessionId, 'qr', { dataUri: session.qrDataUri });
      }

      if (connection === 'open') {
        session.status = 'ready';
        session.qrDataUri = null;
        session.retryCount = 0;

        const userJid = sock.user?.id || 'Connected';
        console.log(`[Session:${sessionId}] 🚀 WhatsApp is READY & Connected as: ${userJid}`);

        broadcast(sessionId, 'authenticated', {});
        broadcast(sessionId, 'ready', {});
      }

      if (connection === 'close') {
        const statusCode = lastDisconnect?.error instanceof Boom
          ? lastDisconnect.error.output?.statusCode
          : null;

        const shouldReconnect = statusCode !== DisconnectReason.loggedOut;

        console.warn(
          `[Session:${sessionId}] ⚠️ Connection closed. Code: ${statusCode}, Reconnect: ${shouldReconnect}`
        );

        broadcast(sessionId, 'disconnected', { reason: lastDisconnect?.error?.message, statusCode });

        if (shouldReconnect) {
          session.status = 'reconnecting';
          session.retryCount = (session.retryCount || 0) + 1;
          const delay = Math.min(3000 * session.retryCount, 15000);
          console.log(`[Session:${sessionId}] Reconnecting in ${delay / 1000}s...`);
          setTimeout(() => {
            bootSession(sessionId, true).catch((e) => {
              console.error(`[Session:${sessionId}] Reconnect failed: ${e.message}`);
            });
          }, delay);
        } else {
          session.status = 'disconnected';
          session.qrDataUri = null;
          console.log(`[Session:${sessionId}] Logged out / QR expired. Purging stale auth state...`);
          try {
            if (fs.existsSync(sessionPath)) {
              fs.rmSync(sessionPath, { recursive: true, force: true });
            }
          } catch (_) {}
        }
      }
    });

    // Handle incoming messages
    sock.ev.on('messages.upsert', async ({ messages: newMessages, type }) => {
      if (type !== 'notify') return;

      for (const m of newMessages) {
        // Skip outbound messages, status broadcasts, and reactions
        if (m.key.fromMe || !m.message) continue;

        const remoteJid = m.key.remoteJid || '';
        if (
          remoteJid === 'status@broadcast' ||
          remoteJid.endsWith('@broadcast') ||
          remoteJid.endsWith('@g.us')
        ) {
          continue;
        }

        const body = extractMessageBody(m.message);
        const fromWaId = toLegacyWaId(remoteJid);
        const timestamp = m.messageTimestamp
          ? Number(m.messageTimestamp)
          : Math.floor(Date.now() / 1000);

        console.log(`[Session:${sessionId}] 📩 Incoming message from ${fromWaId}: "${body}"`);

        // 1. Broadcast to SSE subscribers
        broadcast(sessionId, 'message', {
          message: {
            id: m.key.id,
            from: fromWaId,
            to: sock.user?.id ? toLegacyWaId(sock.user.id) : null,
            body,
            type: 'chat',
            timestamp,
            fromMe: false,
          },
        });

        // 2. Forward to Laravel Webhook
        await forwardToWebhook(fromWaId, body, timestamp);
      }
    });

  } catch (err) {
    session.status = 'error';
    console.error(`[Session:${sessionId}] Initialization error:`, err.message);
    broadcast(sessionId, 'error', { message: err.message });
  }

  return session;
}

// -----------------------------------------------------------------------------
// Auto-start persisted & default sessions
// -----------------------------------------------------------------------------
async function autoStartSessions() {
  const persisted = discoverPersistedSessions(SESSION_DIR);
  if (persisted.length > 0) {
    for (const id of persisted) {
      try {
        console.log(`[Sidecar] Auto-starting persisted session: ${id}`);
        await bootSession(id);
      } catch (err) {
        console.error(`[Sidecar] Failed auto-starting session ${id}: ${err.message}`);
      }
    }
  }

  if (AUTO_START_DEFAULT && !sessions.has('default')) {
    try {
      console.log(`[Sidecar] Auto-starting 'default' session...`);
      await bootSession('default');
    } catch (err) {
      console.error(`[Sidecar] Failed auto-starting default session: ${err.message}`);
    }
  }
}

// -----------------------------------------------------------------------------
// Express App & Endpoints
// -----------------------------------------------------------------------------
const app = express();
app.use(express.json({ limit: '50mb' }));
app.use(express.urlencoded({ extended: true, limit: '50mb' }));
app.use(auth);

app.get('/health', (_, res) => {
  res.json({
    ok: true,
    engine: 'baileys',
    sessions: sessions.size,
    uptime: process.uptime(),
    webhookUrl: WEBHOOK_URL,
  });
});

app.get('/sessions', (_, res) => {
  res.json(
    [...sessions.entries()].map(([id, s]) => ({
      id,
      status: s.status,
      hasQr: !!s.qrDataUri,
    }))
  );
});

app.post('/sessions/:id/start', async (req, res, next) => {
  try {
    let s = sessions.get(req.params.id);
    if (!s || s.status === 'disconnected' || s.status === 'error') {
      s = await bootSession(req.params.id, true);
    }
    // Wait for fresh QR code or ready state so Laravel gets the image immediately
    await waitForQrOrReady(s, 10000);
    res.json({
      id: req.params.id,
      status: s.status,
      qr: s.qrDataUri,
    });
  } catch (e) {
    next(e);
  }
});

app.post('/sessions/:id/stop', async (req, res, next) => {
  try {
    const s = getSession(req.params.id);
    if (s.sock) {
      try {
        s.sock.end(undefined);
      } catch (_) {}
    }
    s.status = 'disconnected';
    sessions.delete(req.params.id);
    res.json({ ok: true });
  } catch (e) {
    next(e);
  }
});

app.delete('/sessions/:id', async (req, res, next) => {
  try {
    const s = sessions.get(req.params.id);
    if (s && s.sock) {
      try {
        s.sock.logout();
      } catch (_) {}
      sessions.delete(req.params.id);
    }
    const authDir = path.join(SESSION_DIR, `session-${req.params.id}`);
    if (fs.existsSync(authDir)) {
      fs.rmSync(authDir, { recursive: true, force: true });
    }
    res.json({ ok: true });
  } catch (e) {
    next(e);
  }
});

app.get('/sessions/:id/qr', async (req, res, next) => {
  try {
    let s = sessions.get(req.params.id);
    if (!s || s.status === 'disconnected') {
      s = await bootSession(req.params.id, true);
    }
    if (!s.qrDataUri && s.status !== 'ready') {
      await waitForQrOrReady(s, 8000);
    }
    res.json({ status: s.status, qr: s.qrDataUri });
  } catch (e) {
    next(e);
  }
});

app.get('/sessions/:id/status', (req, res, next) => {
  try {
    const s = getSession(req.params.id);
    res.json({ id: req.params.id, status: s.status });
  } catch (e) {
    next(e);
  }
});

/**
 * Message send endpoint (matching Laravel package)
 */
app.post('/sessions/:id/messages', async (req, res, next) => {
  try {
    const s = getSession(req.params.id);
    requireReady(s);

    const b = req.body || {};
    const recipientJid = toBaileysJid(b.to);

    if (!recipientJid) {
      return res.status(400).json({ error: 'invalid recipient (to)' });
    }

    console.log(`[Send] To: ${recipientJid} | Type: ${b.type || 'text'}`);

    let sendOptions = {};
    if (b.quotedMessageId) {
      sendOptions.quoted = { key: { id: b.quotedMessageId } };
    }

    let result;
    const msgType = b.type || 'text';

    switch (msgType) {
      case 'text':
      default:
        result = await s.sock.sendMessage(
          recipientJid,
          { text: b.body ?? '' },
          sendOptions
        );
        break;

      case 'image':
      case 'video':
      case 'audio':
      case 'document': {
        let buffer;
        if (b.url) {
          buffer = await fetchBuffer(b.url);
        } else if (b.base64) {
          buffer = Buffer.from(b.base64, 'base64');
        } else {
          return res.status(400).json({ error: 'media requires url or base64' });
        }

        let mediaPayload;
        if (msgType === 'image') {
          mediaPayload = { image: buffer, caption: b.caption };
        } else if (msgType === 'video') {
          mediaPayload = { video: buffer, caption: b.caption };
        } else if (msgType === 'audio') {
          mediaPayload = {
            audio: buffer,
            mimetype: b.mimeType || 'audio/mp4',
            ptt: b.sendAudioAsVoice === true,
          };
        } else if (msgType === 'document') {
          mediaPayload = {
            document: buffer,
            mimetype: b.mimeType || 'application/octet-stream',
            fileName: b.filename || 'file',
            caption: b.caption,
          };
        }

        result = await s.sock.sendMessage(recipientJid, mediaPayload, sendOptions);
        break;
      }
    }

    res.json({
      id: result?.key?.id ?? null,
      from: s.sock.user?.id ? toLegacyWaId(s.sock.user.id) : null,
      to: b.to,
      body: b.body ?? null,
      type: msgType,
      timestamp: Math.floor(Date.now() / 1000),
      fromMe: true,
      ok: true,
    });
  } catch (e) {
    console.error('[Send Error]', e);
    next(e);
  }
});

/**
 * SSE Events Stream
 */
app.get('/sessions/:id/events', (req, res, next) => {
  try {
    const s = getSession(req.params.id);
    res.setHeader('Content-Type', 'text/event-stream');
    res.setHeader('Cache-Control', 'no-cache');
    res.setHeader('Connection', 'keep-alive');
    res.flushHeaders?.();

    s.subscribers.add(res);

    res.write(`event: connected\ndata: ${JSON.stringify({ sessionId: req.params.id, status: s.status })}\n\n`);

    req.on('close', () => {
      s.subscribers.delete(res);
    });
  } catch (e) {
    next(e);
  }
});

// Error handling middleware
app.use((err, req, res, next) => {
  const status = err.http || 500;
  res.status(status).json({
    error: err.message || 'internal server error',
  });
});

// -----------------------------------------------------------------------------
// Graceful Shutdown
// -----------------------------------------------------------------------------
function cleanupAndExit(signal) {
  console.log(`\n[SIGMA-3 WA] Received ${signal}. Stopping Baileys sessions gracefully...`);
  for (const [id, s] of sessions.entries()) {
    try {
      if (s.sock) {
        s.sock.end(undefined);
      }
    } catch (_) {}
  }
  process.exit(0);
}

process.on('SIGINT', () => cleanupAndExit('SIGINT'));
process.on('SIGTERM', () => cleanupAndExit('SIGTERM'));

// -----------------------------------------------------------------------------
// Start Express Server
// -----------------------------------------------------------------------------
app.listen(PORT, HOST, async () => {
  console.log(`🚀 SIGMA-3 WhatsApp Sidecar (Baileys) listening at http://${HOST}:${PORT}`);
  await autoStartSessions();
});
