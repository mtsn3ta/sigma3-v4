@php
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $data = LaporanBulananHelper::anggota($record);
    $sessions = $data['sessions'];
    $members = $data['members'];
    $attendance = $data['attendance'];
    $statistik = LaporanBulananHelper::statistik($record);
@endphp

<div class="page-container mt-30">

    {{-- Judul BAB --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            BAB II
        </h1>
        <h2
            style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0.25rem 0 0 0; letter-spacing: 0.05em;">
            PELAKSANAAN PROGRAM PEMBINAAN
        </h2>
    </div>

    {{-- ========================================================= --}}
    {{-- 2.1 Jadwal Pembinaan --}}
    {{-- ========================================================= --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            2.1 Jadwal Pelaksanaan Pembinaan
        </h3>

        <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; color: #111827;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 5%; text-align: center;">No</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 18%;">Tanggal</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 15%; text-align: center;">Waktu</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 20%;">Pelatih</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem;">Program Latihan</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 12%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $session)
                    <tr>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            {{ $session->tanggal->translatedFormat('d F Y') }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ substr($session->waktu_mulai, 0, 5) }} - {{ substr($session->waktu_selesai, 0, 5) }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            {{ $session->pelatih?->nama ?? '-' }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            {!! $session->program_latihan ?: '-' !!}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ ucfirst($session->status) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            style="border: 1px solid #374151; padding: 1rem; text-align: center; font-style: italic;">
                            Tidak ada sesi pembinaan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 2.2 Rekap Kehadiran Anggota --}}
    {{-- ========================================================= --}}
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            2.2 Rekap Kehadiran Anggota
        </h3>

        <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.8125rem; color: #111827;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="border: 1px solid #374151; padding: 0.4rem; width: 5%; text-align: center;">No</th>
                    <th style="border: 1px solid #374151; padding: 0.4rem; width: 25%; text-align: left;">Nama Anggota
                    </th>

                    @foreach ($sessions as $session)
                        <th style="border: 1px solid #374151; padding: 0.4rem; text-align: center; font-size: 0.75rem;">
                            {{ $session->tanggal->format('d/m') }}
                        </th>
                    @endforeach

                    <th style="border: 1px solid #374151; padding: 0.4rem; text-align: center;">Hadir</th>
                    <th style="border: 1px solid #374151; padding: 0.4rem; text-align: center;">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    @php
                        $rows = $attendance[$member->siswa_id] ?? collect();
                        $hadir = 0;
                    @endphp
                    <tr>
                        <td style="border: 1px solid #374151; padding: 0.4rem; text-align: center;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.4rem;">
                            {{ $member->siswa->nama }}
                        </td>

                        @foreach ($sessions as $session)
                            @php
                                $status = $rows[$session->id]->status ?? null;
                                if ($status === 'hadir') {
                                    $hadir++;
                                }
                            @endphp
                            <td
                                style="border: 1px solid #374151; padding: 0.4rem; text-align: center; font-weight: bold;">
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

                        <td style="border: 1px solid #374151; padding: 0.4rem; text-align: center; font-weight: bold;">
                            {{ $hadir }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.4rem; text-align: center;">
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
        </div>

        {{-- Legenda --}}
        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #374151;">
            <strong style="margin-right: 0.5rem;">Keterangan:</strong>
            <span style="margin-right: 1rem;"><strong>✓</strong> = Hadir</span>
            <span style="margin-right: 1rem;"><strong>I</strong> = Izin</span>
            <span style="margin-right: 1rem;"><strong>S</strong> = Sakit</span>
            <span><strong>A</strong> = Alpha</span>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 2.3 Statistik Pembinaan --}}
    {{-- ========================================================= --}}
    <div>
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            2.3 Statistik Pembinaan
        </h3>

        <div class="table-responsive">
        <table style="width: 100%; max-width: 500px; border-collapse: collapse; font-size: 0.875rem; color: #111827;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="border: 1px solid #374151; padding: 0.5rem; text-align: left; width: 70%;">Uraian</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; text-align: center; width: 30%;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #374151; padding: 0.5rem;">Jumlah Anggota Aktif</td>
                    <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center; font-weight: bold;">
                        {{ $statistik['jumlah_anggota'] }} Orang
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #374151; padding: 0.5rem;">Jumlah Sesi Pembinaan</td>
                    <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center; font-weight: bold;">
                        {{ $statistik['jumlah_sesi'] }} Kali
                    </td>
                </tr>
                <tr>
                    <td style="border: 1px solid #374151; padding: 0.5rem;">Persentase Kehadiran Total</td>
                    <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center; font-weight: bold;">
                        {{ $statistik['persentase_kehadiran'] }}%
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

</div>
