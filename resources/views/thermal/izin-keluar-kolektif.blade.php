@extends('thermal.layout')

@section('title', 'Dispensasi Kolektif')

@section('content')

    <div class="title">
        DISPENSASI KOLEKTIF
    </div>

    <div class="subtitle">
        MTsN 3 Tulungagung
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td class="label">Waktu Izin</td>
            <td class="separator">:</td>
            <td class="value">
                @php
                    $mulai = $masterRecord->tanggal->translatedFormat('d F Y');

                    if (
                        $masterRecord->tanggal_selesai &&
                        !$masterRecord->tanggal->isSameDay($masterRecord->tanggal_selesai)
                    ) {
                        $selesai = $masterRecord->tanggal_selesai->translatedFormat('d F Y');
                        echo "{$mulai} s.d. {$selesai}";
                    } else {
                        echo $mulai;
                    }
                @endphp
            </td>
        </tr>

        <tr>
            <td class="label">Jam Keluar</td>
            <td class="separator">:</td>
            <td class="value">
                {{ \Carbon\Carbon::parse($masterRecord->jam_keluar)->format('H:i') }}
            </td>
        </tr>

        <tr>
            <td class="label">Tujuan</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $masterRecord->tujuan }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="bold">
        Keperluan
    </div>

    <div class="text-wrap">
        {{ $masterRecord->keperluan }}
    </div>

    <div class="line"></div>

    <div class="bold">
        DAFTAR SISWA ({{ $records->count() }} Orang)
    </div>

    <table>
        @foreach ($records as $index => $record)
            <tr>
                {{-- Angka urut --}}
                <td style="width: 15px; vertical-align: top;">{{ $index + 1 }}.</td>
                {{-- Nama dan Kelas --}}
                <td class="value" style="padding-bottom: 5px;">
                    {{ $record->siswa->nama }} <br>
                    Kelas: {{ $record->siswa->kelasAktif?->kelas?->nama_kelas ?? '-' }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <tr>
            <td class="label">Petugas</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $masterRecord->pegawai->nama }}
            </td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="footer">
        Saat kembali ke madrasah,
        surat ini wajib diserahkan
        kepada Guru BK / Guru Piket.
    </div>

    <div class="footer">
        Dicetak<br>
        {{ now('Asia/Jakarta')->format('d-m-Y H:i') }} WIB
    </div>

@endsection
