<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Monitoring Ekstrakurikuler</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 5px;
            vertical-align: top;
        }

        th {
            background: #efefef;
        }

        .stat {
            margin-bottom: 12px;
        }

        .stat td {
            text-align: center;
            font-weight: bold;
        }
    </style>

</head>

<body>
    @include('pdf.components.header')
    <h2>

        MONITORING EKSTRAKURIKULER

    </h2>

    <table style="margin-bottom:12px;border:none;">

        <tr>

            <td style="border:none;width:18%;">

                <strong>Tahun Ajaran</strong>

            </td>

            <td style="border:none;">

                : {{ $tahunAjaran->tahun }}

            </td>

        </tr>

        <tr>

            <td style="border:none;">

                <strong>Semester</strong>

            </td>

            <td style="border:none;">

                : {{ ucfirst($semester) }}

            </td>

        </tr>

    </table>

    <table class="stat">

        <tr>

            <td>

                Ekstrakurikuler<br>

                {{ $totalEkskul }}

            </td>

            <td>

                Pembina<br>

                {{ $totalPembina }}

            </td>

            <td>

                Pelatih<br>

                {{ $totalPelatih }}

            </td>

            <td>

                Anggota<br>

                {{ $totalAnggota }}

            </td>

        </tr>

    </table>

    <table>

        <thead>

            <tr>

                <th width="5%">No</th>

                <th width="28%">Ekstrakurikuler</th>

                <th width="22%">Pembina</th>

                <th width="22%">Pelatih</th>

                <th width="13%">Hari</th>

                <th width="10%">Anggota</th>

            </tr>

        </thead>

        <tbody>

            @foreach ($data as $row)
                <tr>

                    <td align="center">

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $row->nama }}

                    </td>

                    <td>

                        {{ $row->pengampu->where('role', 'pembina')->pluck('pegawai.nama')->filter()->implode(', ') ?: '-' }}

                    </td>

                    <td>

                        {{ $row->pengampu->where('role', 'pelatih')->pluck('pegawai.nama')->filter()->implode(', ') ?: '-' }}

                    </td>

                    <td align="center">

                        {{ collect($row->jadwal)->implode(', ') }}

                    </td>

                    <td align="center">

                        {{ $row->anggotaEkskul->count() }}

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>
    @include('pdf.components.footer')
</body>

</html>
