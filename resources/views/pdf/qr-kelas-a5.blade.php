<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Code Presensi Kelas A5</title>
    <style>
        @page {
            size: A5 landscape;
            margin: 8mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }
        .card {
            width: 100%;
            height: 94%;
            box-sizing: border-box;
            border: 3px solid #064e3b;
            border-radius: 16px;
            padding: 14px 18px;
            background-color: #ffffff;
            page-break-after: always;
        }
        .card:last-child {
            page-break-after: avoid;
        }
        .table-layout {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .left-col {
            width: 50%;
            vertical-align: middle;
            padding-right: 14px;
        }
        .right-col {
            width: 50%;
            vertical-align: middle;
            text-align: center;
            border-left: 1.5px dashed #cbd5e1;
            padding-left: 14px;
        }
        .sub-header {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #059669;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .school-title {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .class-badge {
            display: inline-block;
            background-color: #064e3b;
            color: #ffffff;
            font-size: 26px;
            font-weight: 900;
            padding: 4px 22px;
            border-radius: 10px;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }
        .instruction-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 8.5px;
            color: #334155;
            line-height: 1.5;
        }
        .step-num {
            display: inline-block;
            width: 13px;
            height: 13px;
            line-height: 13px;
            background: #059669;
            color: #fff;
            border-radius: 50%;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
            margin-right: 4px;
        }
        .qr-image {
            width: 175px;
            height: 175px;
            display: block;
            margin: 0 auto;
        }
        .qr-caption {
            font-size: 9px;
            font-weight: 800;
            color: #064e3b;
            text-transform: uppercase;
            margin-top: 6px;
            letter-spacing: 0.5px;
        }
        .footer-note {
            font-size: 7.5px;
            color: #94a3b8;
            margin-top: 8px;
        }
    </style>
</head>
<body>

    @foreach($items as $item)
        <div class="card">
            <table class="table-layout">
                <tr>
                    <td class="left-col">
                        <div class="sub-header">PRESENSI MENGAJAR GURU</div>
                        <div class="school-title">{{ $schoolName }}</div>

                        <div>
                            <span class="class-badge">KELAS {{ $item['kelas']->nama_kelas }}</span>
                        </div>

                        <div class="instruction-box">
                            <div style="margin-bottom: 3px;">
                                <span class="step-num">1</span> Buka Kamera HP / Aplikasi SIGMA3.
                            </div>
                            <div style="margin-bottom: 3px;">
                                <span class="step-num">2</span> Arahkan kamera ke QR di samping.
                            </div>
                            <div>
                                <span class="step-num">3</span> Kehadiran langsung aktif di TV Ruang Guru.
                            </div>
                        </div>

                        <div class="footer-note">
                            MTsN 3 Tulungagung • Stand Akrilik Meja Guru
                        </div>
                    </td>
                    <td class="right-col">
                        <img src="{{ $item['qr_data_uri'] }}" class="qr-image" alt="QR Code Kelas {{ $item['kelas']->nama_kelas }}">
                        <div class="qr-caption">SCAN UNTUK CHECK-IN KELAS</div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

</body>
</html>
