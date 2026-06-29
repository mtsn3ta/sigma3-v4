@extends('thermal.layout')

@section('title', 'Tugas Guru Izin')

@section('content')

    <div class="title">
        TUGAS GURU BERHALANGAN
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
                {{ $tugas->tanggal->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td class="label">Guru</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $tugas->guru->nama }}
            </td>
        </tr>

        <tr>
            <td class="label">Kelas</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $tugas->kelas->nama_kelas }}
            </td>
        </tr>

        <tr>
            <td class="label">Mapel</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $tugas->mapel }}
            </td>
        </tr>

        <tr>
            <td class="label">Jam</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $tugas->jam_ke }}
            </td>
        </tr>

    </table>

    <div class="line"></div>

    <div class="bold">
        Tugas Pembelajaran
    </div>

    <div class="text-wrap">
        {{ $tugas->isi_tugas }}
    </div>

    <div class="line"></div>

    <table>

        <tr>
            <td class="label">Status</td>
            <td class="separator">:</td>
            <td class="value">
                {{ strtoupper($tugas->status_label) }}
            </td>
        </tr>

        <tr>
            <td class="label">Disampaikan</td>
            <td class="separator">:</td>
            <td class="value">
                {{ $tugas->petugas?->nama ?? '-' }}
            </td>
        </tr>

    </table>

    <div class="line"></div>

    <div class="footer">

        Tugas ini disampaikan oleh
        Guru Piket kepada kelas
        yang bersangkutan.

    </div>

    @if ($tugas->updated_at)
        <div class="footer">

            Dicetak<br>

            {{ now()->format('d-m-Y H:i') }}

        </div>
    @endif

@endsection
