<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Monitoring Izin Keluar</title>

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

        .status-kembali {
            color: green;
            font-weight: bold;
        }

        .status-belum {
            color: red;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>

    @foreach ($data as $halaman)
        <h2>

            MONITORING IZIN KELUAR SISWA

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
                            Kelas
                        </th>

                        <th width="18%">
                            Siswa
                        </th>

                        <th width="10%">
                            Keluar
                        </th>

                        <th width="10%">
                            Kembali
                        </th>

                        <th width="27%">
                            Keperluan
                        </th>

                        <th width="10%">
                            Petugas
                        </th>

                        <th width="8%">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($rows as $row)
                        <tr>

                            <td align="center">

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                {{ $row->siswa?->nama_kelas_aktif ?? '-' }}

                            </td>

                            <td>

                                {{ $row->siswa?->nama }}

                            </td>

                            <td align="center">

                                {{ $row->jam_keluar_formatted ?? '-' }}

                            </td>

                            <td align="center">

                                {{ $row->jam_kembali_formatted ?? '-' }}

                            </td>

                            <td>

                                {!! nl2br(e($row->keperluan)) !!}

                            </td>

                            <td>

                                {{ $row->pegawai?->nama ?? '-' }}

                            </td>

                            <td align="center">

                                @if ($row->jam_kembali)
                                    <span class="status-kembali">

                                        Sudah

                                    </span>
                                @else
                                    <span class="status-belum">

                                        Belum

                                    </span>
                                @endif

                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>
        @endforeach

        @unless ($loop->last)
            <div class="page-break"></div>
        @endunless
    @endforeach

</body>

</html>
