/**
 * SIGMA-3 WhatsApp Automation Sidecar
 * MTsN 3 Tulungagung
 *
 * Microservice wrapping whatsapp-web.js to handle:
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
const { Client, LocalAuth, MessageMedia, Location } = require('whatsapp-web.js');
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
console.log('🤖 SIGMA-3 WhatsApp Sidecar starting...');
console.log(`📡 Host/Port      : ${HOST}:${PORT}`);
console.log(`🔗 Webhook URL    : ${WEBHOOK_URL}`);
console.log(`🔐 Webhook Secret : ${WEBHOOK_SECRET ? 'Configured (Hidden)' : 'None'}`);
console.log(`🔑 Bearer Token   : ${TOKEN ? 'Configured (Hidden)' : 'None'}`);
console.log(`📁 Session Dir    : ${SESSION_DIR}`);
console.log('----------------------------------------------------');

/**
 * Detect suitable Chromium executable path on Linux
 */
function getChromiumExecutablePath() {
  if (process.env.WHATSAPP_WEB_CHROME_PATH && fs.existsSync(process.env.WHATSAPP_WEB_CHROME_PATH)) {
    return process.env.WHATSAPP_WEB_CHROME_PATH;
  }
  if (process.env.PUPPETEER_EXECUTABLE_PATH && fs.existsSync(process.env.PUPPETEER_EXECUTABLE_PATH)) {
    return process.env.PUPPETEER_EXECUTABLE_PATH;
  }

  // Common Ubuntu/Debian paths
  const linuxPaths = [
    '/usr/bin/chromium-browser',
    '/usr/bin/chromium',
    '/usr/bin/google-chrome-stable',
    '/usr/bin/google-chrome',
    '/snap/bin/chromium',
  ];

  for (const p of linuxPaths) {
    if (fs.existsSync(p)) {
      return p;
    }
  }

  return undefined; // Let Puppeteer use its bundled binary if available
}

/** sessionId → { client, status, qrDataUri, subscribers: Set<res> } */
const sessions = new Map();

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
  if (session.status !== 'ready') {
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

function serializeMessage(m) {
  if (!m) return null;
  return {
    id: m.id?._serialized ?? null,
    from: m.from,
    to: m.to,
    body: m.body,
    type: m.type,
    timestamp: m.timestamp,
    hasMedia: m.hasMedia,
    isForwarded: m.isForwarded,
    isStatus: m.isStatus,
    isStarred: m.isStarred,
    fromMe: m.fromMe,
    author: m.author,
    deviceType: m.deviceType,
  };
}

/**
 * Normalizes WhatsApp recipient ID:
 * 08123456789 -> 628123456789@c.us
 * +628123456789 -> 628123456789@c.us
 * 628123456789@c.us -> 628123456789@c.us
 */
function normalizeWaId(input) {
  if (!input || typeof input !== 'string') return input;
  if (input.includes('@')) return input;
  let digits = input.replace(/\D+/g, '');
  if (!digits) return input;
  if (digits.startsWith('0')) {
    digits = '62' + digits.slice(1);
  }
  return `${digits}@c.us`;
}

/**
 * Forward message to Laravel Webhook via HTTP POST
 */
async function forwardToWebhook(message) {
  if (!WEBHOOK_URL) return;

  const payload = {
    event: 'message',
    data: {
      message: {
        from: message.from,
        body: message.body ?? '',
        fromMe: message.fromMe ?? false,
        isStatus: message.isStatus ?? false,
        type: message.type,
        timestamp: message.timestamp,
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
        'User-Agent': 'SIGMA3-WhatsApp-Sidecar/1.0',
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
          console.log(`[Webhook] Message from ${message.from} forwarded successfully (HTTP ${res.statusCode})`);
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
      console.warn(`[Webhook] Request timeout forwarding message from ${message.from}`);
    });

    req.write(payloadString);
    req.end();
  } catch (err) {
    console.error(`[Webhook] Forwarding exception: ${err.message}`);
  }
}

// -----------------------------------------------------------------------------
// Boot session
// -----------------------------------------------------------------------------
async function bootSession(sessionId) {
  const existing = sessions.get(sessionId);
  if (existing) return existing;

  const executablePath = getChromiumExecutablePath();
  if (executablePath) {
    console.log(`[Session:${sessionId}] Using Chromium at: ${executablePath}`);
  }

  const client = new Client({
    authStrategy: new LocalAuth({ clientId: sessionId, dataPath: SESSION_DIR }),
    puppeteer: {
      headless: true,
      executablePath,
      args: [
        '--no-sandbox',
        '--disable-setuid-sandbox',
        '--disable-dev-shm-usage',
        '--disable-accelerated-2d-canvas',
        '--no-first-run',
        '--no-zygote',
        '--disable-gpu',
      ],
    },
  });

  const session = {
    client,
    status: 'initializing',
    qrDataUri: null,
    subscribers: new Set(),
  };
  sessions.set(sessionId, session);

  client.on('qr', async (qr) => {
    session.qrDataUri = await qrcode.toDataURL(qr);
    session.status = 'qr';

    console.log('\n======================================================');
    console.log(`[Session:${sessionId}] 📲 QR CODE GENERATED!`);
    console.log(`Scan in WhatsApp > Linked Devices or open /admin/whatsapp/qr`);
    console.log('======================================================\n');

    try {
      const terminalQr = await qrcode.toString(qr, { type: 'terminal', small: true });
      console.log(terminalQr);
    } catch (_) {}

    broadcast(sessionId, 'qr', { dataUri: session.qrDataUri });
  });

  client.on('authenticated', () => {
    session.status = 'authenticated';
    console.log(`[Session:${sessionId}] ✅ Authenticated!`);
    broadcast(sessionId, 'authenticated', {});
  });

  client.on('auth_failure', (msg) => {
    session.status = 'auth_failure';
    console.error(`[Session:${sessionId}] ❌ Authentication failure:`, msg);
    broadcast(sessionId, 'auth_failure', { message: msg });
  });

  client.on('ready', () => {
    session.status = 'ready';
    session.qrDataUri = null;
    console.log(`[Session:${sessionId}] 🚀 WhatsApp Client is READY & Connected!`);
    broadcast(sessionId, 'ready', {});
  });

  client.on('disconnected', (reason) => {
    session.status = 'disconnected';
    console.warn(`[Session:${sessionId}] ⚠️ Disconnected:`, reason);
    broadcast(sessionId, 'disconnected', { reason });
  });

  client.on('message', async (m) => {
    // Ignore outbound messages, status broadcasts, and group chats for CS bot
    if (m.fromMe || m.isStatus || m.from?.endsWith('@g.us')) {
      return;
    }

    console.log(`[Session:${sessionId}] 📩 Incoming message from ${m.from}: "${m.body}"`);

    // 1. Broadcast to SSE subscribers
    broadcast(sessionId, 'message', { message: serializeMessage(m) });

    // 2. Forward to Laravel Webhook
    await forwardToWebhook(m);
  });

  client.on('message_ack', (m, ack) => {
    broadcast(sessionId, 'message_ack', { id: m.id?._serialized, ack });
  });

  client.initialize().catch((e) => {
    session.status = 'error';
    console.error(`[Session:${sessionId}] Initialization error:`, e.message);
    broadcast(sessionId, 'error', { message: e.message });
  });

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

  // Ensure default session is booted if not already
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
app.use(auth);

app.get('/health', (_, res) => {
  res.json({
    ok: true,
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
    const s = await bootSession(req.params.id);
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
    try {
      await s.client.destroy();
    } catch (_) {}
    sessions.delete(req.params.id);
    res.json({ ok: true });
  } catch (e) {
    next(e);
  }
});

app.delete('/sessions/:id', async (req, res, next) => {
  try {
    const s = sessions.get(req.params.id);
    if (s) {
      try {
        await s.client.destroy();
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

app.get('/sessions/:id/qr', (req, res, next) => {
  try {
    const s = getSession(req.params.id);
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
    const to = normalizeWaId(b.to);

    console.log(`[Send] To: ${to} | Type: ${b.type || 'text'}`);

    let result;

    switch (b.type) {
      case 'text':
      default:
        result = await s.client.sendMessage(
          to,
          b.body ?? '',
          b.quotedMessageId ? { quotedMessageId: b.quotedMessageId } : {}
        );
        break;

      case 'image':
      case 'video':
      case 'audio':
      case 'document': {
        let media;
        if (b.url) {
          media = await MessageMedia.fromUrl(b.url, { unsafeMime: true });
        } else if (b.base64) {
          media = new MessageMedia(b.mimeType || 'application/octet-stream', b.base64, b.filename);
        } else {
          return res.status(400).json({ error: 'media requires url or base64' });
        }
        result = await s.client.sendMessage(to, media, {
          caption: b.caption,
          sendAudioAsVoice: b.sendAudioAsVoice === true,
        });
        break;
      }
    }

    res.json(serializeMessage(result) || { ok: true });
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
// Start Express Server
// -----------------------------------------------------------------------------
app.listen(PORT, HOST, async () => {
  console.log(`🚀 SIGMA-3 WhatsApp Sidecar listening at http://${HOST}:${PORT}`);
  await autoStartSessions();
});
