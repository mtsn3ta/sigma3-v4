<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp QR — SIGMA-3</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: linear-gradient(135deg, #ccfbf1 0%, #e0f2fe 50%, #d1fae5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            width: 100%;
            max-width: 28rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0.35) 100%);
            backdrop-filter: blur(40px) saturate(150%);
            -webkit-backdrop-filter: blur(40px) saturate(150%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 1.75rem;
            box-shadow: 0 25px 50px -12px rgba(13, 148, 136, 0.15), inset 0 1px 1px rgba(255, 255, 255, 0.9);
            padding: 2.5rem 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            background: #ecfdf5;
            color: #059669;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .badge__dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 9999px;
            background: #22c55e;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.375rem;
        }

        .subtitle {
            font-size: 0.875rem;
            color: #64748b;
        }

        .qr-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
        }

        .qr-wrap img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #475569;
        }

        .status strong {
            color: #0f172a;
        }

        .empty {
            text-align: center;
            padding: 1.5rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 1rem;
        }

        .empty h2 {
            font-size: 1rem;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 0.5rem;
        }

        .empty p {
            font-size: 0.875rem;
            color: #b91c1c;
            margin-bottom: 1rem;
        }

        .empty pre {
            text-align: left;
            font-size: 0.75rem;
            background: #fff;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 0.75rem;
            overflow-x: auto;
            color: #475569;
        }

        .actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: #0d9488;
            color: #fff;
            box-shadow: 0 8px 16px -4px rgba(13, 148, 136, 0.3);
        }

        .btn-primary:hover {
            background: #0f766e;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #fff;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }

        .meta {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="badge">
                <span class="badge__dot"></span>
                WhatsApp Gateway
            </div>
            <h1>Scan QR Code</h1>
            <p class="subtitle">Hubungkan sesi WhatsApp untuk notifikasi SIGMA-3</p>
        </div>

        @if (($data['status'] ?? '') === 'ready')
            <div style="text-align: center; padding: 2rem 1.25rem; background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 1.25rem; margin-bottom: 1.5rem;">
                <div style="font-size: 3.5rem; margin-bottom: 0.75rem;">✅</div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #065f46; margin-bottom: 0.375rem;">WhatsApp Berhasil Terhubung!</h2>
                <p style="font-size: 0.875rem; color: #047857; margin-bottom: 1rem; line-height: 1.5;">Bot <strong>CS AI SIGMA-3</strong> aktif online dan siap melayani 24 Jam.</p>
                <div style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.875rem; border-radius: 9999px; background: #059669; color: #ffffff; font-size: 0.75rem; font-weight: 600;">
                    <span style="width: 0.5rem; height: 0.5rem; border-radius: 9999px; background: #86efac;"></span>
                    STATUS: READY (ONLINE)
                </div>
            </div>
        @elseif (! empty($data['qr']))
            <div class="qr-wrap">
                <img src="{{ $data['qr'] }}" alt="QR Code WhatsApp">
                <div class="status">
                    Status sesi: <strong>{{ $data['status'] ?? 'Menunggu scan' }}</strong>
                </div>
            </div>
        @else
            <div class="empty">
                <h2>Menunggu Sesi WhatsApp</h2>
                <p>Status sesi: <b>{{ $data['status'] ?? 'Memulai...' }}</b>. Jika sedang menghubungkan, klik refresh untuk memperbarui status.</p>
                @if (! empty($data))
                    <pre>{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                @endif
            </div>
        @endif

        <div class="actions">
            <button type="button" class="btn btn-primary" onclick="location.reload()">
                Refresh QR
            </button>
            <a href="{{ url('/admin') }}" class="btn btn-secondary">
                Kembali ke Admin
            </a>
        </div>

        <p class="meta">HTTP {{ $httpCode }}</p>
    </div>
</body>
</html>
