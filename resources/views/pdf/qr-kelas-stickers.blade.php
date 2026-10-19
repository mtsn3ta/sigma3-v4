<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stiker QR Code Presensi Kelas</title>
    <style>
        @page {
            margin: 15mm 12mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px;
        }
        .sticker-card {
            border: 2px solid #0f172a;
            border-radius: 12px;
            padding: 14px 10px;
            text-align: center;
            background-color: #ffffff;
            width: 48%;
            box-sizing: border-box;
            vertical-align: top;
        }
        .header-title {
            font-size: 10px;
            font-weight: bold;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .school-name {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .class-badge {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff;
            font-size: 20px;
            font-weight: 900;
            padding: 4px 18px;
            border-radius: 8px;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .qr-image {
            width: 140px;
            height: 140px;
            margin: 0 auto 6px auto;
            display: block;
        }
        .instruction {
            font-size: 9px;
            font-weight: bold;
            color: #334155;
            margin-top: 4px;
        }
        .sub-instruction {
            font-size: 7.5px;
            color: #64748b;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <table class="grid-table">
        @php
            $chunks = array_chunk($items, 2);
        @endphp

        @foreach($chunks as $row)
            <tr>
                @foreach($row as $col)
                    <td class="sticker-card">
                        <div class="header-title">PRESENSI MENGAJAR KELAS</div>
                        <div class="school-name">MTsN 3 TULUNGAGUNG • SIGMA3</div>
                        
                        <div class="class-badge">KELAS {{ $col['kelas']->nama_kelas }}</div>
                        
                        <div>
                            <img src="{{ $col['qr_data_uri'] }}" class="qr-image" alt="QR Code">
                        </div>

                        <div class="instruction">PINDAI MENGGUNAKAN HP GURU</div>
                        <div class="sub-instruction">Buka Kamera HP / Aplikasi SIGMA3 untuk Check-in Mengajar</div>
                    </td>
                @endforeach
                @if(count($row) === 1)
                    <td style="width: 48%;"></td>
                @endif
            </tr>
        @endforeach
    </table>

</body>
</html>
