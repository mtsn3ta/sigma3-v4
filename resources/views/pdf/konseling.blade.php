<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .title {
            text-align: center;
            margin-bottom: 25px;
            line-height: 1.2;
        }

        .title h2 {
            margin: 0;
            font-size: 16px;
        }

        .title h3 {
            margin: 0;
            font-size: 13px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .identitas td {
            border: 1px solid #000;
            padding: 5px;
        }

        .data {
            margin-top: 25px;
        }

        .data th,
        .data td {
            border: 1px solid #000;
            padding: 6px;
        }

        .data th {
            text-align: center;
            background: #f5f5f5;
        }

        .center {
            text-align: center;
        }

        .footer {
            width: 260px;
            float: right;
            text-align: center;
            margin-top: 35px;
        }

        .ttd {
            margin-top: 60px;
        }

        .line {
            width: 150px;
            border-top: 1px solid #000;
            margin: auto;
            margin-top: 45px;
        }
    </style>

</head>

<body>

    <div class="title">

        <h2>LEMBAR KONSELING SISWA</h2>

        <h3>MTsN 3 Tulungagung</h3>

    </div>

    <table class="identitas">

        <tr>

            <td width="25%">Nama</td>

            <td>{{ $siswa->nama }}</td>

        </tr>

        <tr>

            <td>Kelas</td>

            <td>{{ $siswa->nama_kelas_aktif }}</td>

        </tr>

        <tr>

            <td>Tanggal Cetak</td>

            <td>{{ now()->format('d/m/Y') }}</td>

        </tr>

    </table>

    <table class="data">

        <thead>

            <tr>

                <th width="5%">No</th>

                <th width="15%">Tanggal</th>

                <th width="35%">Permasalahan</th>

                <th width="35%">Penyelesaian</th>

                <th width="10%">TTD</th>

            </tr>

        </thead>

        <tbody>

            @forelse ($konseling as $item)
                <tr>

                    <td class="center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="center">
                        {{ $item->tanggal->format('d/m/Y') }}
                    </td>

                    <td>
                        {{ $item->permasalahan }}
                    </td>

                    <td>
                        {{ $item->hasil_konseling }}
                    </td>

                    <td></td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="center">

                        Belum ada data konseling.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    <div class="footer">

        Tulungagung,
        {{ now()->translatedFormat('d F Y') }}
        <br>

        Guru BK

        <div class="ttd"></div>

        <div class="line"></div>

    </div>

</body>

</html>
