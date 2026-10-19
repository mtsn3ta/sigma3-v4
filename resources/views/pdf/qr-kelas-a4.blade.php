<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Poster QR Code Presensi Kelas A4</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 14mm 12mm 14mm 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .poster-card {
            border: 3.5px solid #064e3b;
            border-radius: 16px;
            padding: 24px 20px 20px 20px;
            background-color: #ffffff;
            page-break-after: always;
            page-break-inside: avoid;
        }
        .poster-card:last-child {
            page-break-after: avoid;
        }
        .header-sub {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            color: #059669;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .header-title {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .divider {
            width: 70px;
            height: 3px;
            background-color: #059669;
            margin: 6px auto 14px auto;
            border-radius: 2px;
        }
        .class-badge {
            display: inline-block;
            background-color: #064e3b;
            color: #ffffff;
            font-size: 32px;
            font-weight: 900;
            padding: 6px 36px;
            border-radius: 10px;
            letter-spacing: 2px;
            margin-bottom: 16px;
        }
        .qr-container {
            margin: 0 auto 16px auto;
            display: inline-block;
            padding: 14px;
            border: 2px solid #cbd5e1;
            border-radius: 14px;
            background: #ffffff;
        }
        .qr-img {
            width: 320px;
            height: 320px;
            display: block;
        }
        .scan-title {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .instructions {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            max-width: 480px;
            margin: 0 auto 14px auto;
            text-align: left;
        }
        .step {
            font-size: 11px;
            color: #334155;
            line-height: 1.5;
            margin-bottom: 3px;
        }
        .badge-num {
            display: inline-block;
            width: 17px;
            height: 17px;
            line-height: 17px;
            border-radius: 50%;
            background-color: #059669;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            margin-right: 6px;
        }
        .footer-text {
            font-size: 9.5px;
            color: #64748b;
        }
    </style>
</head>
<body>

    @foreach($items as $item)
        <div class="poster-card">
            <div class="header-sub">PRESENSI MENGAJAR GURU DI KELAS</div>
            <div class="header-title">{{ $schoolName }}</div>
            <div class="divider"></div>

            <div>
                <span class="class-badge">KELAS {{ $item['kelas']->nama_kelas }}</span>
            </div>

            <div>
                <div class="qr-container">
                    <img src="{{ $item['qr_data_uri'] }}" class="qr-img" alt="QR Code Kelas {{ $item['kelas']->nama_kelas }}">
                </div>
            </div>

            <div class="scan-title">PINDAI MENGGUNAKAN HP GURU</div>

            <div class="instructions">
                <div class="step"><span class="badge-num">1</span> Buka Kamera HP atau menu <strong>Scan Masuk Kelas</strong> di SIGMA3.</div>
                <div class="step"><span class="badge-num">2</span> Arahkan kamera ke QR Code di atas saat tiba di kelas.</div>
                <div class="step"><span class="badge-num">3</span> Status kehadiran otomatis berubah <strong>HIJAU</strong> di TV Ruang Guru.</div>
            </div>

            <div class="footer-text">
                Sistem Manajemen Madrasah Terpadu (SIGMA3) &bull; Layar Monitoring Ruang Guru Live
            </div>
        </div>
    @endforeach

</body>
</html>
