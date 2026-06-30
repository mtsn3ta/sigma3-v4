<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Monitoring Tugas Guru Izin</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin: 0 0 8px;
        }

        .info {
            margin-bottom: 10px;
        }

        .info table {
            width: 100%;
        }

        .hari {
            margin-top: 10px;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 12px;
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

        .status-baru {
            color: #d32f2f;
            font-weight: bold;
        }

        .status-disampaikan {
            color: #f57c00;
            font-weight: bold;
        }

        .status-selesai {
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

            MONITORING TUGAS GURU IZIN

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

                        <th width="6%">
                            No
                        </th>

                        <th width="12%">
                            Kelas
                        </th>

                        <th width="10%">
                            Jam Ke
                        </th>

                        <th width="20%">
                            Guru
                        </th>

                        <th width="37%">
                            Isi Tugas
                        </th>

                        <th width="15%">
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

                                {{ $row->kelas?->nama_kelas }}

                            </td>

                            <td align="center">

                                {{ $row->jam_ke }}

                            </td>

                            <td>

                                {{ $row->guru?->nama }}

                            </td>

                            <td>

                                {!! nl2br(e($row->isi_tugas)) !!}

                            </td>

                            <td align="center">

                                @switch($row->status)
                                    @case('baru')
                                        <span class="status-baru">

                                            Baru

                                        </span>
                                    @break

                                    @case('disampaikan')
                                        <span class="status-disampaikan">

                                            Disampaikan

                                        </span>
                                    @break

                                    @case('selesai')
                                        <span class="status-selesai">

                                            Selesai

                                        </span>
                                    @break

                                    @default
                                        -
                                @endswitch

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
