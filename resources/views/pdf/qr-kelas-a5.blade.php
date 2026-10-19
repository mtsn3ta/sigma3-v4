<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Poster QR Code Presensi Kelas A5</title>
    <style>
        @page {
            size: a5 portrait;
            margin: 10mm 8mm 10mm 8mm;
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
            border: 3px solid #064e3b;
            border-radius: 14px;
            padding: 16px 12px 14px 12px;
            background-color: #ffffff;
            page-break-after: always;
            page-break-inside: avoid;
        }
        .poster-card:last-child {
            page-break-after: avoid;
        }
        .header-sub {
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #059669;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .header-title {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .divider {
            width: 50px;
            height: 2px;
            background-color: #059669;
            margin: 4px auto 8px auto;
            border-radius: 2px;
        }
        .class-badge {
            display: inline-block;
            background-color: #064e3b;
            color: #ffffff;
            font-size: 22px;
            font-weight: 900;
            padding: 4px 24px;
            border-radius: 8px;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }
        .qr-container {
            margin: 0 auto 10px auto;
            display: inline-block;
            padding: 8px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
        }
        .qr-img {
            width: 210px;
            height: 210px;
            display: block;
        }
        .scan-title {
            font-size: 11px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .instructions {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            max-width: 320px;
            margin: 0 auto 8px auto;
            text-align: left;
        }
        .step {
            font-size: 8px;
            color: #334155;
            line-height: 1.4;
            margin-bottom: 2px;
        }
        .badge-num {
            display: inline-block;
            width: 13px;
            height: 13px;
            line-height: 13px;
            border-radius: 50%;
            background-color: #059669;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            font-size: 7.5px;
            margin-right: 4px;
        }
        .footer-text {
            font-size: 7.5px;
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
                Sistem Manajemen Madrasah Terpadu (SIGMA3) &bull; Stand Akrilik Meja Guru A5
            </div>
        </div>
    @endforeach

</body>
</html>
