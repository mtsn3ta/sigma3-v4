@extends('thermal.layout')

@section('title', 'Izin Keluar')

@section('content')

    <div class="title">
        IZIN KELUAR SISWA
    </div>

    <div class="subtitle">
        MTsN 3 Tulungagung
    </div>

    <div class="line"></div>

    <table>

        <tr>
            <td class="label">Tanggal</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $izinKeluar->tanggal->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td class="label">Nama</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $izinKeluar->siswa->nama }}
            </td>
        </tr>

        <tr>
            <td class="label">Kelas</td>
            <td class="separator">:</td>
            <td class="value">
                {{-- PERBAIKAN 1: Menggunakan jalur relasi objek database murni agar nama kelas (misal: IX-A) tidak kosong --}}
                {{ $izinKeluar->siswa->kelasAktif?->kelas?->nama_kelas ?? '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">Jam Keluar</td>
            <td class="separator">:</td>
            <td class="value">
                {{ \Carbon\Carbon::parse($izinKeluar->jam_keluar)->format('H:i') }}
            </td>
        </tr>

        @if ($izinKeluar->jam_kembali)
            <tr>
                <td class="label">Jam Kembali</td>
                <td class="separator">:</td>
                <td class="value">
                    {{ \Carbon\Carbon::parse($izinKeluar->jam_kembali)->format('H:i') }}
                </td>
            </tr>
        @endif

        <tr>
            <td class="label">Tujuan</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $izinKeluar->tujuan }}
            </td>
        </tr>

    </table>

    <div class="line"></div>

    <div class="bold">
        Keperluan
    </div>

    <div class="text-wrap">
        {{ $izinKeluar->keperluan }}
    </div>

    <div class="line"></div>

    <table>

        <tr>
            <td class="label">Petugas</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $izinKeluar->pegawai->nama }}
            </td>
        </tr>

        <tr>
            <td class="label">Status</td>
            <td class="separator">:</td>
            <td class="value">
                {{ strtoupper($izinKeluar->status_proses) }}
            </td>
        </tr>

    </table>

    <div class="line"></div>

    <div class="footer">

        Saat kembali ke madrasah,
        surat ini wajib diserahkan
        kepada Guru BK / Guru Piket.

    </div>

    {{-- PERBAIKAN 2: Mengunci pencetakan waktu rill kertas keluar ke Asia/Jakarta (WIB) agar jamnya akurat --}}
    <div class="footer">

        Dicetak<br>

        {{ now('Asia/Jakarta')->format('d-m-Y H:i') }} WIB

    </div>

@endsection
