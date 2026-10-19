<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Poster QR Code Presensi Kelas A4</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .page {
            width: 100%;
            height: 96%;
            box-sizing: border-box;
            border: 4px solid #064e3b;
            border-radius: 20px;
            padding: 24px 20px;
            position: relative;
            background-color: #ffffff;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .inner-border {
            border: 1.5px dashed #059669;
            border-radius: 14px;
            padding: 20px 16px;
            height: 93%;
            box-sizing: border-box;
        }
        .sub-header {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #059669;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .school-title {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 1px;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .divider {
            width: 80px;
            height: 3px;
            background-color: #059669;
            margin: 8px auto 14px auto;
            border-radius: 2px;
        }
        .class-badge-container {
            margin-bottom: 16px;
        }
        .class-badge {
            display: inline-block;
            background-color: #064e3b;
            color: #ffffff;
            font-size: 34px;
            font-weight: 900;
            padding: 6px 36px;
            border-radius: 12px;
            letter-spacing: 2px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .qr-wrapper {
            margin: 10px auto 16px auto;
            display: inline-block;
            padding: 12px;
            background: #ffffff;
            border: 2px solid #cbd5e1;
            border-radius: 16px;
        }
        .qr-image {
            width: 290px;
            height: 290px;
            display: block;
        }
        .action-title {
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .instruction-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 16px;
            max-width: 480px;
            margin: 0 auto 12px auto;
            text-align: left;
        }
        .instruction-step {
            font-size: 10.5px;
            color: #334155;
            line-height: 1.6;
            margin-bottom: 3px;
        }
        .step-number {
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
        .footer-note {
            font-size: 9px;
            color: #64748b;
            font-style: italic;
            margin-top: 6px;
        }
    </style>
</head>
<body>

    @foreach($items as $item)
        <div class="page">
            <div class="inner-border">
                <div class="sub-header">PRESENSI MENGAJAR GURU DI KELAS</div>
                <div class="school-title">{{ $schoolName }}</div>
                <div class="divider"></div>

                <div class="class-badge-container">
                    <div class="class-badge">KELAS {{ $item['kelas']->nama_kelas }}</div>
                </div>

                <div class="qr-wrapper">
                    <img src="{{ $item['qr_data_uri'] }}" class="qr-image" alt="QR Code Kelas {{ $item['kelas']->nama_kelas }}">
                </div>

                <div class="action-title">PINDAI MENGGUNAKAN HP GURU</div>

                <div class="instruction-box">
                    <div class="instruction-step">
                        <span class="step-number">1</span> Buka Kamera HP / Menu <strong>Scan Masuk Kelas</strong> di SIGMA3.
                    </div>
                    <div class="instruction-step">
                        <span class="step-number">2</span> Arahkan kamera ke QR Code di atas saat tiba di kelas.
                    </div>
                    <div class="instruction-step">
                        <span class="step-number">3</span> Status kehadiran otomatis berubah <strong>HIJAU</strong> di TV Ruang Guru.
                    </div>
                </div>

                <div class="footer-note">
                    Sistem Manajemen Madrasah Terpadu (SIGMA3) • Layar Monitoring Ruang Guru Live
                </div>
            </div>
        </div>
    @endforeach

</body>
</html>
