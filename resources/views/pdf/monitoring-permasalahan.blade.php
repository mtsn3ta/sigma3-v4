<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Monitoring Permasalahan Siswa</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 8px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        .statistik {
            margin-bottom: 12px;
        }

        .statistik table {
            width: 100%;
            border-collapse: collapse;
        }

        .statistik td {
            border: 1px solid #555;
            padding: 6px;
            text-align: center;
        }

        .hari {
            margin-top: 12px;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: bold;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table.data th,
        table.data td {
            border: 1px solid #444;
            padding: 4px;
            vertical-align: top;
        }

        table.data th {
            background: #efefef;
            text-align: center;
        }

        .danger {
            color: #d32f2f;
            font-weight: bold;
        }

        .warning {
            color: #f57c00;
            font-weight: bold;
        }

        .success {
            color: #2e7d32;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>

    @foreach ($data as $halaman)
        @include('pdf.components.header')
        <h2>

            MONITORING PERMASALAHAN SISWA

        </h2>

        <div class="info">

            <table>

                <tr>

                    <td width="15%">

                        <strong>Periode</strong>

                    </td>

                    <td>

                        :

                        {{ $tanggalMulai->translatedFormat('d F Y') }}

                        s/d

                        {{ $tanggalSelesai->translatedFormat('d F Y') }}

                    </td>

                </tr>

                <tr>

                    <td>

                        <strong>Kelas</strong>

                    </td>

                    <td>

                        :

                        {{ $kelas->nama_kelas }}

                    </td>

                </tr>

            </table>

        </div>

        <div class="statistik">

            <table>

                <tr>

                    <td>

                        <strong>Total Permasalahan</strong><br>

                        {{ $totalPermasalahan }}

                    </td>

                    <td>

                        <strong>Siswa Bermasalah</strong><br>

                        {{ $totalSiswa }}

                    </td>

                    <td>

                        <strong>Total Poin</strong><br>

                        {{ $totalPoin }}

                    </td>

                </tr>

            </table>

        </div>

        @foreach ($halaman as $tanggal => $rows)
            <div class="hari">

                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}

            </div>

            <table class="data">

                <thead>

                    <tr>

                        <th width="5%">
                            No
                        </th>

                        <th width="12%">
                            Tanggal
                        </th>

                        <th width="20%">
                            Siswa
                        </th>

                        <th width="40%">
                            Permasalahan
                        </th>

                        <th width="8%">
                            Poin
                        </th>

                        <th width="15%">
                            Guru BK
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($rows as $row)
                        <tr>

                            <td align="center">

                                {{ $loop->iteration }}

                            </td>

                            <td align="center">

                                {{ $row->tanggal->translatedFormat('d M Y') }}

                            </td>

                            <td>

                                {{ $row->siswa?->nama }}

                            </td>

                            <td>

                                {!! nl2br(e($row->permasalahan)) !!}

                            </td>

                            <td align="center">

                                @if ($row->poin >= 75)
                                    <span class="danger">

                                        {{ $row->poin }}

                                    </span>
                                @elseif($row->poin >= 50)
                                    <span class="warning">

                                        {{ $row->poin }}

                                    </span>
                                @else
                                    <span class="success">

                                        {{ $row->poin }}

                                    </span>
                                @endif

                            </td>

                            <td>

                                {{ $row->pegawai?->nama }}

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        @endforeach

        @unless ($loop->last)
            <div class="page-break"></div>
        @endunless
        @include('pdf.components.footer')
    @endforeach

</body>

</html>
