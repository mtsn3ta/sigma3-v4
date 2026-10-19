<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Poster QR Code Presensi Kelas A5</title>
    <style>
        @page {
            size: A5 portrait;
            margin: 8mm;
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
            border: 3.5px solid #064e3b;
            border-radius: 16px;
            padding: 16px 14px;
            background-color: #ffffff;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .inner-border {
            border: 1.5px dashed #059669;
            border-radius: 12px;
            padding: 14px 10px;
            height: 94%;
            box-sizing: border-box;
        }
        .sub-header {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #059669;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .school-title {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .divider {
            width: 50px;
            height: 2.5px;
            background-color: #059669;
            margin: 6px auto 10px auto;
            border-radius: 2px;
        }
        .class-badge-container {
            margin-bottom: 10px;
        }
        .class-badge {
            display: inline-block;
            background-color: #064e3b;
            color: #ffffff;
            font-size: 24px;
            font-weight: 900;
            padding: 5px 26px;
            border-radius: 10px;
            letter-spacing: 1.5px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.12);
        }
        .qr-wrapper {
            margin: 6px auto 10px auto;
            display: inline-block;
            padding: 8px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 14px;
        }
        .qr-image {
            width: 205px;
            height: 205px;
            display: block;
        }
        .action-title {
            font-size: 12px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .instruction-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            max-width: 320px;
            margin: 0 auto 8px auto;
            text-align: left;
        }
        .instruction-step {
            font-size: 8.5px;
            color: #334155;
            line-height: 1.5;
            margin-bottom: 2px;
        }
        .step-number {
            display: inline-block;
            width: 14px;
            height: 14px;
            line-height: 14px;
            border-radius: 50%;
            background-color: #059669;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            margin-right: 4px;
        }
        .footer-note {
            font-size: 7.5px;
            color: #64748b;
            font-style: italic;
            margin-top: 4px;
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
                    Sistem Manajemen Madrasah Terpadu (SIGMA3) • Stand Akrilik Meja Guru A5
                </div>
            </div>
        </div>
    @endforeach

</body>
</html>
