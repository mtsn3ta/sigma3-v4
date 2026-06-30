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
            background: #f5f5f5;
            text-align: center;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
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

        <h2>

            LEMBAR PERMASALAHAN SISWA

        </h2>

        <h3>

            {{ \App\Support\SettingHelper::schoolName() }}

        </h3>

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

                <th width="12%">Tanggal</th>

                <th width="32%">Permasalahan</th>

                <th width="42%">Penyelesaian</th>

                <th width="8%">Poin</th>

                <th width="8%">TTD</th>

            </tr>

        </thead>

        <tbody>

            @forelse ($permasalahan as $item)
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
                        {{ $item->penyelesaian }}
                    </td>

                    <td class="center">
                        {{ $item->poin }}
                    </td>

                    <td></td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="center">

                        Belum ada data permasalahan.

                    </td>

                </tr>
            @endforelse

            <tr>

                <td colspan="4" class="right">

                    <strong>Total Poin</strong>

                </td>

                <td class="center">

                    <strong>{{ $totalPoin }}</strong>

                </td>

                <td></td>

            </tr>

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
