<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Monitoring Jurnal Mengajar</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        .summary {
            margin: 6px 0 10px;
            font-size: 10px;
        }

        .summary td {
            padding: 4px;
            border: 1px solid #444;
            text-align: center;
            font-weight: bold;
        }

        table.timeline {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table.timeline th,
        table.timeline td {
            border: 1px solid #444;
            padding: 4px;
            vertical-align: top;
        }

        table.timeline th {
            background: #efefef;
            text-align: center;
        }

        .judul-hari {
            margin-top: 10px;
            margin-bottom: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-mengajar {
            color: green;
            font-weight: bold;
        }

        .status-izin {
            color: #d97706;
            font-weight: bold;
        }

        .status-kosong {
            color: red;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>

    @foreach ($monitoring as $halaman)
        <h2>
            MONITORING JURNAL MENGAJAR
        </h2>

        <div class="info">

            <table>

                <tr>

                    <td width="15%">
                        <strong>Periode</strong>
                    </td>

                    <td width="35%">
                        :
                        {{ $tanggalMulai->translatedFormat('d F Y') }}
                        s/d
                        {{ $tanggalSelesai->translatedFormat('d F Y') }}
                    </td>

                    <td width="10%">
                        <strong>Kelas</strong>
                    </td>

                    <td>
                        :
                        {{ $kelas->nama_kelas }}
                    </td>

                </tr>

            </table>

        </div>

        @foreach ($halaman as $hari)
            <div class="judul-hari">

                {{ $hari['tanggal']->translatedFormat('l, d F Y') }}

            </div>

            <table class="summary">

                <tr>

                    <td>

                        Mengajar

                        <br>

                        {{ $hari['totalMengajar'] }}

                    </td>

                    <td>

                        Guru Izin

                        <br>

                        {{ $hari['totalIzin'] }}

                    </td>

                    <td>

                        Belum Isi

                        <br>

                        {{ $hari['totalKosong'] }}

                    </td>

                </tr>

            </table>

            <table class="timeline">

                <thead>

                    <tr>

                        <th width="7%">
                            Jam
                        </th>

                        <th width="12%">
                            Status
                        </th>

                        <th width="18%">
                            Guru
                        </th>

                        <th width="12%">
                            Mapel
                        </th>

                        <th width="23%">
                            Materi / Tugas
                        </th>

                        <th width="18%">
                            Tidak Hadir
                        </th>

                        <th width="10%">
                            Catatan
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($hari['timeline'] as $row)
                        <tr>

                            <td align="center">

                                {{ $row['jam'] }}

                            </td>

                            <td align="center">

                                @switch($row['status'])
                                    @case('mengajar')
                                        <span class="status-mengajar">

                                            Mengajar

                                        </span>
                                    @break

                                    @case('izin')
                                        <span class="status-izin">

                                            Guru Izin

                                        </span>
                                    @break

                                    @default
                                        <span class="status-kosong">

                                            Belum Isi

                                        </span>
                                @endswitch

                            </td>

                            <td>

                                {{ $row['guru'] }}

                            </td>

                            <td>

                                {{ $row['mapel'] }}

                            </td>

                            <td>

                                {!! nl2br(e($row['materi'])) !!}

                            </td>

                            <td>

                                {!! nl2br(e($row['tidak_hadir'])) !!}

                            </td>

                            <td>

                                {!! nl2br(e($row['catatan'])) !!}

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
