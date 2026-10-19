<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Jurnal Mengajar Guru</title>

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
            border-collapse: collapse;
        }

        .info td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .ringkasan {
            margin: 6px 0 10px;
        }

        .ringkasan td {
            padding: 5px;
            border: 1px solid #444;
            text-align: center;
            font-weight: bold;
        }

        table.jurnal {
            width: 100%;
            border-collapse: collapse;
        }

        table.jurnal th,
        table.jurnal td {
            border: 1px solid #444;
            padding: 4px;
            vertical-align: top;
        }

        table.jurnal th {
            background: #efefef;
            text-align: center;
        }

        .kosong {
            text-align: center;
            padding: 20px;
            border: 1px solid #444;
            font-style: italic;
        }

        .dokumentasi {
            color: #2563eb;
            font-size: 9px;
        }
    </style>

</head>

<body>

    @include('pdf.components.header')

    <h2>JURNAL MENGAJAR GURU</h2>

    <div class="info">
        <table>
            <tr>
                <td width="18%"><strong>Nama Guru</strong></td>
                <td width="32%">: {{ $pegawai->nama }}</td>
                <td width="18%"><strong>Periode</strong></td>
                <td>: {{ $mulai->translatedFormat('d F Y') }} s/d {{ $selesai->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>NIP</strong></td>
                <td>: {{ $pegawai->nik_nip ?? '-' }}</td>
                <td><strong>Jumlah Jurnal</strong></td>
                <td>: {{ $jurnal->count() }} entri</td>
            </tr>
        </table>
    </div>

    <table class="ringkasan">
        <tr>
            <td>Total Jurnal<br>{{ $jurnal->count() }}</td>
            <td>Total Jam<br>{{ $jurnal->sum(fn ($item) => is_numeric($item->jam_ke) ? (int) $item->jam_ke : 1) }}</td>
            <td>Jurnal dengan Dokumentasi<br>
                {{ $jurnal->filter(fn ($item) => $item->dokumentasi_foto || $item->dokumentasi_video)->count() }}
            </td>
            <td>Ada Siswa Tidak Ikut<br>{{ $jurnal->filter(fn ($item) => $item->kehadiranPembelajaran->isNotEmpty())->count() }}</td>
        </tr>
    </table>

    @if ($jurnal->isEmpty())
        <div class="kosong">
            Tidak ada jurnal mengajar pada periode ini.
        </div>
    @else
        <table class="jurnal">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="9%">Tanggal</th>
                    <th width="6%">Jam</th>
                    <th width="8%">Kelas</th>
                    <th width="11%">Mapel</th>
                    <th width="27%">Materi</th>
                    <th width="22%">Siswa Tidak Ikut</th>
                    <th width="13%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jurnal as $index => $item)
                    <tr>
                        <td align="center">{{ $index + 1 }}</td>
                        <td align="center">{{ $item->tanggal?->translatedFormat('d/m/Y') }}</td>
                        <td align="center">{{ $item->jam_ke }}</td>
                        <td align="center">{{ $item->kelas?->nama_kelas ?? '-' }}</td>
                        <td>{{ $item->mapel }}</td>
                        <td>
                            {!! nl2br(e($item->materi ?: '-')) !!}
                            @if ($item->dokumentasi_foto || $item->dokumentasi_video)
                                <div class="dokumentasi">* ada dokumentasi KBM</div>
                            @endif
                        </td>
                        <td>
                            @forelse ($item->kehadiranPembelajaran as $kehadiran)
                                {{ $kehadiran->siswa?->nama ?? '-' }} ({{ $kehadiran->status_label }})
                                @if (! $loop->last)
                                    <br>
                                @endif
                            @empty
                                <span>Semua hadir</span>
                            @endforelse
                        </td>
                        <td>{!! nl2br(e($item->catatan ?: '-')) !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @include('pdf.components.footer')

</body>

</html>
