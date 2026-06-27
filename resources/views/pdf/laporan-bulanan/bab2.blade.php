@php

    use App\Support\LaporanBulananHelper;

    $data = LaporanBulananHelper::anggota($record);

    $sessions = $data['sessions'];

    $members = $data['members'];

    $attendance = $data['attendance'];

    $statistik = LaporanBulananHelper::statistik($record);

@endphp

<div>

    <div style="
            text-align:center;
            margin-bottom:40px;
        ">

        <div
            style="
                font-size:22px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            BAB II

        </div>

        <div
            style="
                margin-top:10px;
                font-size:18px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            PELAKSANAAN PROGRAM PEMBINAAN

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- 2.1 Jadwal Pembinaan --}}
    {{-- ========================================================= --}}

    <h4 style="margin-top:25px;">
        2.1 Jadwal Pelaksanaan Pembinaan
    </h4>

    <table
        style="
        width:100%;
        border-collapse:collapse;
        font-size:13px;
        margin-top:10px;
    ">

        <thead>

            <tr>

                <th class="border" width="5%">
                    No
                </th>

                <th class="border" width="18%">
                    Tanggal
                </th>

                <th class="border" width="12%">
                    Waktu
                </th>

                <th class="border" width="18%">
                    Pelatih
                </th>

                <th class="border" width="35%">
                    Program Latihan
                </th>

                <th class="border" width="12%">
                    Status
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($sessions as $session)
                <tr>

                    <td class="border" align="center">

                        {{ $loop->iteration }}

                    </td>

                    <td class="border">

                        {{ $session->tanggal->translatedFormat('d F Y') }}

                    </td>

                    <td class="border" align="center">

                        {{ substr($session->waktu_mulai, 0, 5) }}

                        -

                        {{ substr($session->waktu_selesai, 0, 5) }}

                    </td>

                    <td class="border">

                        {{ $session->pelatih?->nama ?? '-' }}

                    </td>

                    <td class="border">

                        {!! $session->program_latihan ?: '-' !!}

                    </td>

                    <td class="border" align="center">

                        {{ ucfirst($session->status) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="border" align="center">

                        Tidak ada sesi pembinaan pada periode ini.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    {{-- ========================================================= --}}
    {{-- 2.2 Rekap Kehadiran Anggota --}}
    {{-- ========================================================= --}}

    <h4 style="margin-top:35px;">
        2.2 Rekap Kehadiran Anggota
    </h4>

    <table
        style="
        width:100%;
        border-collapse:collapse;
        font-size:12px;
        margin-top:10px;
    ">

        <thead>

            <tr>

                <th class="border" width="5%">
                    No
                </th>

                <th class="border" width="25%">
                    Nama Anggota
                </th>

                @foreach ($sessions as $session)
                    <th class="border" style="font-size:10px;">

                        {{ $session->tanggal->format('d/m') }}

                    </th>
                @endforeach

                <th class="border">
                    Hadir
                </th>

                <th class="border">
                    %
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach ($members as $member)
                @php

                    $rows = $attendance[$member->siswa_id] ?? collect();

                    $hadir = 0;

                @endphp

                <tr>

                    <td class="border" align="center">

                        {{ $loop->iteration }}

                    </td>

                    <td class="border">

                        {{ $member->siswa->nama }}

                    </td>

                    @foreach ($sessions as $session)
                        @php

                            $status = $rows[$session->id]->status ?? null;

                            if ($status === 'hadir') {
                                $hadir++;
                            }

                        @endphp

                        <td class="border" align="center">

                            @switch($status)
                                @case('hadir')
                                    ✓
                                @break

                                @case('izin')
                                    I
                                @break

                                @case('sakit')
                                    S
                                @break

                                @case('alpha')
                                    A
                                @break

                                @default
                                    -
                            @endswitch

                        </td>
                    @endforeach

                    <td class="border" align="center">

                        {{ $hadir }}

                    </td>

                    <td class="border" align="center">

                        @if ($sessions->count())
                            {{ round(($hadir / $sessions->count()) * 100) }}%
                        @else
                            -
                        @endif

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

    <p style="margin-top:8px;font-size:11px;">

        <strong>Keterangan :</strong>

        ✓ = Hadir &nbsp;&nbsp;

        I = Izin &nbsp;&nbsp;

        S = Sakit &nbsp;&nbsp;

        A = Alpha

    </p>

    {{-- ========================================================= --}}
    {{-- 2.3 Statistik Pembinaan --}}
    {{-- ========================================================= --}}

    <h4 style="margin-top:35px;">
        2.3 Statistik Pembinaan
    </h4>

    <table
        style="
        width:70%;
        border-collapse:collapse;
        font-size:13px;
        margin-top:10px;
    ">

        <thead>

            <tr>

                <th class="border" style="width:70%;">
                    Uraian
                </th>

                <th class="border" style="width:30%;">
                    Nilai
                </th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td class="border">
                    Jumlah Anggota Aktif
                </td>

                <td class="border" align="center">

                    {{ $statistik['jumlah_anggota'] }}

                </td>

            </tr>

            <tr>

                <td class="border">
                    Jumlah Sesi Pembinaan
                </td>

                <td class="border" align="center">

                    {{ $statistik['jumlah_sesi'] }}

                </td>

            </tr>

            <tr>

                <td class="border">
                    Persentase Kehadiran
                </td>

                <td class="border" align="center">

                    {{ $statistik['persentase_kehadiran'] }} %

                </td>

            </tr>

        </tbody>

    </table>
</div>
