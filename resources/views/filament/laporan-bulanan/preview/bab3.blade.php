@php
    use Carbon\Carbon;
    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */
    $proposal = LaporanBulananHelper::proposal($record);
    $kegiatan = LaporanBulananHelper::kegiatan($record);
    $prestasi = LaporanBulananHelper::prestasi($record);
@endphp

<div class="page-container mt-30">

    {{-- Judul BAB --}}
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            BAB III
        </h1>
        <h2
            style="font-size: 1.25rem; font-weight: 700; text-transform: uppercase; margin: 0.25rem 0 0 0; letter-spacing: 0.05em;">
            KEGIATAN DAN PRESTASI EKSTRAKURIKULER
        </h2>
    </div>

    {{-- ========================================================= --}}
    {{-- 3.1 Proposal Kegiatan --}}
    {{-- ========================================================= --}}
    <div style="margin-bottom: 2.5rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            3.1 Proposal Kegiatan
        </h3>

        <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; color: #111827;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 5%; text-align: center;">No</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; text-align: left;">Nama Kegiatan</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 20%; text-align: center;">Jenis</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 15%; text-align: center;">Tanggal</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 15%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proposal as $item)
                    <tr>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            {{ $item->nama_kegiatan }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $item->jenis_kegiatan_label }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ ucfirst($item->status) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            style="border: 1px solid #374151; padding: 1rem; text-align: center; font-style: italic;">
                            Tidak ada proposal kegiatan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 3.2 Laporan Kegiatan --}}
    {{-- ========================================================= --}}
    <div style="margin-bottom: 2.5rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            3.2 Laporan Kegiatan
        </h3>

        <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; color: #111827;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 5%; text-align: center;">No</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; text-align: left;">Nama Kegiatan</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 20%; text-align: center;">Tanggal</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 15%; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kegiatan as $item)
                    <tr>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            {{ $item->proposal?->nama_kegiatan ?? '-' }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $item->proposal?->tanggal_mulai ? Carbon::parse($item->proposal->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ ucfirst($item->status) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            style="border: 1px solid #374151; padding: 1rem; text-align: center; font-style: italic;">
                            Tidak ada laporan kegiatan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- 3.3 Prestasi --}}
    {{-- ========================================================= --}}
    <div>
        <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0 0 1rem 0;">
            3.3 Prestasi
        </h3>

        <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem; color: #111827;">
            <thead style="background-color: #f3f4f6;">
                <tr>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 5%; text-align: center;">No</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; text-align: left;">Nama Lomba</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; text-align: left;">Prestasi</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 12%; text-align: center;">Jenis</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 15%; text-align: center;">Tingkat</th>
                    <th style="border: 1px solid #374151; padding: 0.5rem; width: 15%; text-align: center;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestasi as $item)
                    <tr>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            {{ $item->nama_lomba }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem;">
                            <strong>{{ $item->nama_prestasi }}</strong>
                            @if ($item->siswa->isNotEmpty())
                                <br>
                                <span style="font-size: 0.75rem; color: #4b5563;">
                                    {{ $item->siswa->pluck('nama')->join(', ') }}
                                </span>
                            @endif
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $item->jenis_label }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $item->tingkat }}
                        </td>
                        <td style="border: 1px solid #374151; padding: 0.5rem; text-align: center;">
                            {{ $item->tanggal ? Carbon::parse($item->tanggal)->translatedFormat('d M Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            style="border: 1px solid #374151; padding: 1rem; text-align: center; font-style: italic;">
                            Belum ada prestasi pada bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- Kesimpulan Prestasi --}}
        @if ($prestasi->isNotEmpty())
            @php
                $individu = $prestasi->where('jenis', 'individu')->count();
                $tim = $prestasi->where('jenis', 'tim')->count();
            @endphp
            <div
                style="
                margin-top: 1.5rem;
                padding: 1rem;
                border: 1px solid #d1d5db;
                border-left: 4px solid #374151;
                background-color: #f9fafb;
                font-size: 0.875rem;
                line-height: 1.6;
                text-align: justify;
            ">
                Pada bulan ini ekstrakurikuler <strong>{{ $record->ekstrakurikuler->nama }}</strong>
                memperoleh <strong>{{ $prestasi->count() }}</strong> prestasi,
                terdiri dari <strong>{{ $individu }}</strong> prestasi individu
                dan <strong>{{ $tim }}</strong> prestasi tim.
            </div>
        @endif
    </div>

</div>
