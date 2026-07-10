@php
    use Illuminate\Support\Facades\Storage;

    $pembinaApproved = in_array($record->status, ['pending_waka', 'pending_kepala', 'approved']);
    $wakaApproved = in_array($record->status, ['pending_kepala', 'approved']);
    $kepalaApproved = $record->status === 'approved';

    $pelatihTtd = $record->pelatih()?->pegawai?->ttd_digital;
    $pembinaTtd = $record->pembina()?->pegawai?->ttd_digital;
    $wakaTtd = $record->wakaKesiswaan()?->pegawai?->ttd_digital;
    $kepalaTtd = $record->kepalaMadrasah()?->pegawai?->ttd_digital;

    $signatureImg = function (?string $path, bool $isApproved, float $rotate = 0) {
        if (!$isApproved || blank($path)) {
            return '';
        }

        $url = Storage::disk('public')->url($path);

        return '<img src="' .
            $url .
            '" style="position:absolute; top:60%; left:50%; transform:translate(-50%, -50%) rotate(' .
            $rotate .
            'deg); max-width:230px; max-height:130px; z-index:2; pointer-events:none;">';
    };
@endphp
<x-filament-panels::page>

    {{-- CSS Khusus untuk merapikan hasil Rich Editor dan Print Setup --}}
    <style>
        .rich-text-content p {
            text-align: justify;
            line-height: 1.6;
            font-size: 1rem;
            color: #374151;
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .rich-text-content h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .rich-text-content h4:first-child {
            margin-top: 0;
        }

        .rich-text-content ol {
            list-style-type: decimal;
            margin-top: 0;
            margin-bottom: 1rem;
            padding-left: 2rem;
            color: #374151;
            line-height: 1.6;
        }

        .rich-text-content ul {
            list-style-type: disc;
            margin-top: 0;
            margin-bottom: 1rem;
            padding-left: 2rem;
            color: #374151;
            line-height: 1.6;
        }

        .rich-text-content li {
            margin-bottom: 0.5rem;
            text-align: justify;
            padding-left: 0.25rem;
        }

        .rich-text-content strong {
            font-weight: 600;
            color: #111827;
        }

        @media print {
            .page-container {
                margin: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
            }
        }
    </style>

    <div style="background: #e5e7eb; padding: 40px 0;">

        {{-- ========================================================================= --}}
        {{-- HALAMAN COVER --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 0 auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">

            <div style="text-align: center;">
                @if (\App\Support\SettingHelper::schoolOfficialLogo())
                    <img src="{{ asset('storage/' . \App\Support\SettingHelper::schoolOfficialLogo()) }}"
                        style="display: block; width: 110px; height: auto; margin: 0 auto 20px auto;">
                @endif
                <div style="font-size: 15px; font-weight: bold; text-transform: uppercase;">
                    KEMENTERIAN AGAMA REPUBLIK INDONESIA
                </div>
                <div style="font-size: 26px; font-weight: bold; margin-top: 6px;">
                    {{ \App\Support\SettingHelper::schoolName() }}
                </div>
                <div style="font-size: 18px; margin-top: 5px;">
                    Tahun Pelajaran {{ \App\Support\SettingHelper::tahunAjaranLabel() }}
                </div>
            </div>

            <hr style="margin: 35px 0; border: 1px solid #111827;">

            <div style="text-align: center;">
                <div style="font-size: 28px; font-weight: bold; text-transform: uppercase;">
                    LAPORAN KEGIATAN
                </div>
                <div style="font-size: 22px; margin-top: 12px; font-weight: bold; text-transform: uppercase;">
                    {{ $record->proposal->nama_kegiatan }}
                </div>
            </div>

            <div style="margin-top: 60px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 16px; line-height: 1.8;">
                    <tr>
                        <td style="width: 220px;"><strong>Ekstrakurikuler</strong></td>
                        <td>: {{ $record->proposal->ekstrakurikuler->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kegiatan</strong></td>
                        <td>: {{ $record->proposal->jenis_kegiatan_label }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tempat</strong></td>
                        <td>: {{ $record->proposal->tempat }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><strong>Tanggal Pelaksanaan</strong></td>
                        <td style="vertical-align: top;">
                            : {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }} <br>
                            &nbsp;&nbsp;s.d. {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Laporan</strong></td>
                        <td>: {{ $record->status_label }}</td>
                    </tr>
                </table>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB I : PENDAHULUAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB I</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PENDAHULUAN</h3>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Latar Belakang</h4>
                <div class="rich-text-content">
                    {!! $record->proposal->latar_belakang !!}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Tujuan</h4>
                <div class="rich-text-content">
                    {!! $record->proposal->tujuan !!}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">C. Identitas Kegiatan</h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 1rem; line-height: 1.6;">
                    <tr>
                        <td style="width: 220px; padding: 4px 0;">Nama Kegiatan</td>
                        <td style="padding: 4px 0;">: {{ $record->proposal->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Ekstrakurikuler</td>
                        <td style="padding: 4px 0;">: {{ $record->proposal->ekstrakurikuler->nama }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Jenis Kegiatan</td>
                        <td style="padding: 4px 0;">: {{ $record->proposal->jenis_kegiatan_label }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Tempat</td>
                        <td style="padding: 4px 0;">: {{ $record->proposal->tempat }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Tanggal Pelaksanaan</td>
                        <td style="padding: 4px 0;">
                            : {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }} s.d.
                            {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Jumlah Peserta</td>
                        <td style="padding: 4px 0;">: {{ $record->peserta->count() }} Orang</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Jumlah Pendamping</td>
                        <td style="padding: 4px 0;">: {{ $record->pendamping->count() }} Orang</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB II : PELAKSANAAN KEGIATAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB II</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PELAKSANAAN KEGIATAN</h3>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Waktu dan Tempat</h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 1rem; line-height: 1.6;">
                    <tr>
                        <td style="width: 220px; padding: 4px 0;">Hari / Tanggal</td>
                        <td style="padding: 4px 0;">:
                            {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }} s.d.
                            {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Tempat</td>
                        <td style="padding: 4px 0;">: {{ $record->proposal->tempat }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0;">Jenis Kegiatan</td>
                        <td style="padding: 4px 0;">: {{ $record->proposal->jenis_kegiatan_label }}</td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Pendamping Kegiatan</h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Nama</th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 170px; text-align: center;">Peran
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 90px; text-align: center;">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($record->pendamping as $item)
                            <tr>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #111827; padding: 8px;">{{ $item->pegawai->nama }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ ucfirst(str_replace('_', ' ', $item->peran)) }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ ucfirst($item->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">C. Peserta Kegiatan</h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Nama</th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 80px; text-align: center;">JK
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 120px; text-align: center;">Kelas
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 90px; text-align: center;">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($record->peserta as $item)
                            <tr>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #111827; padding: 8px;">{{ $item->siswa->nama }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $item->siswa->jenis_kelamin }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $item->siswa->kelasAktif?->kelas?->nama }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ ucfirst($item->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">D. Rekap Kehadiran Peserta</h4>
                <table style="width: 50%; border-collapse: collapse; font-size: 0.875rem;">
                    <tr>
                        <td style="border: 1px solid #111827; padding: 8px;">Hadir</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: center; width: 100px;">
                            {{ $record->peserta->where('status', 'hadir')->count() }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #111827; padding: 8px;">Izin</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                            {{ $record->peserta->where('status', 'izin')->count() }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #111827; padding: 8px;">Sakit</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                            {{ $record->peserta->where('status', 'sakit')->count() }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #111827; padding: 8px;">Alpha</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                            {{ $record->peserta->where('status', 'alpha')->count() }}</td>
                    </tr>
                </table>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">E. Uraian Pelaksanaan</h4>
                <div class="rich-text-content">
                    {!! $record->uraian_pelaksanaan !!}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB III : HASIL KEGIATAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB III</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">HASIL KEGIATAN</h3>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Pencapaian</h4>
                <div class="rich-text-content">
                    {!! $record->pencapaian !!}
                </div>
            </div>

            @if (!empty(strip_tags($record->prestasi ?? '')))
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Prestasi</h4>
                    <div class="rich-text-content">
                        {!! $record->prestasi !!}
                    </div>
                </div>
            @endif

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">
                    {{ !empty(strip_tags($record->prestasi ?? '')) ? 'C' : 'B' }}. Evaluasi</h4>
                <div class="rich-text-content">
                    {!! $record->evaluasi !!}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB IV : REALISASI ANGGARAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB IV</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">REALISASI ANGGARAN</h3>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead style="background: #f3f4f6;">
                    <tr>
                        <th style="border: 1px solid #111827; padding: 8px; width: 40px; text-align: center;">No</th>
                        <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Uraian</th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 130px; text-align: right;">Proposal
                        </th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 130px; text-align: right;">Realisasi
                        </th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 130px; text-align: right;">Selisih
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalProposal = 0;
                        $totalRealisasi = 0;
                    @endphp
                    @forelse ($record->realisasiAnggaran as $item)
                        @php
                            $proposal = $item->proposalRab?->subtotal ?? 0;
                            $realisasi = $item->subtotal;
                            $selisih = $proposal - $realisasi;
                            $totalProposal += $proposal;
                            $totalRealisasi += $realisasi;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                {{ $loop->iteration }}</td>
                            <td style="border: 1px solid #111827; padding: 8px;">{{ $item->uraian }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                                {{ number_format($proposal, 0, ',', '.') }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                                {{ number_format($realisasi, 0, ',', '.') }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                                {{ number_format($selisih, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                style="border: 1px solid #111827; padding: 16px; text-align: center; font-style: italic;">
                                Tidak ada data realisasi anggaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #f3f4f6; font-weight: bold;">
                        <td colspan="2" style="border: 1px solid #111827; padding: 8px; text-align: center;">TOTAL
                        </td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                            {{ number_format($totalProposal, 0, ',', '.') }}</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                            {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                            {{ number_format($totalProposal - $totalRealisasi, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : DOKUMENTASI --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">DOKUMENTASI KEGIATAN</h3>
            </div>

            @if (!empty($record->dokumentasi))
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    @foreach ($record->dokumentasi as $foto)
                        @if ($loop->first || $loop->iteration % 2 == 1)
                            <tr>
                        @endif

                        <td style="width: 50%; padding: 0.75rem; text-align: center; vertical-align: top;">
                            <div
                                style="border: 1px solid #e5e7eb; padding: 0.5rem; border-radius: 0.5rem; background-color: #f9fafb;">
                                <img src="{{ Storage::url($foto) }}"
                                    style="max-width: 100%; height: 220px; object-fit: cover; border-radius: 0.25rem; display: block; margin: 0 auto;"
                                    alt="Dokumentasi">
                            </div>
                        </td>

                        @if ($loop->last || $loop->iteration % 2 == 0)
                            </tr>
                        @endif
                    @endforeach
                </table>
            @else
                <div style="text-align: center; font-style: italic; color: #6b7280; margin-top: 40px;">
                    Tidak ada dokumentasi.
                </div>
            @endif

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : BUKTI TRANSAKSI --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">BUKTI TRANSAKSI</h3>
            </div>

            @forelse($record->realisasiAnggaran as $item)
                <div style="margin-bottom: 30px;">
                    <h4 style="font-size: 1rem; font-weight: bold; margin-bottom: 10px;">{{ $loop->iteration }}.
                        {{ $item->uraian }}</h4>
                    @if ($item->bukti)
                        <img src="{{ Storage::url($item->bukti) }}"
                            style="width: 100%; max-height: 400px; object-fit: contain; border: 1px solid #ccc; padding: 4px;">
                    @else
                        <p style="font-style: italic; color: #6b7280;">Tidak ada bukti transaksi.</p>
                    @endif
                </div>
            @empty
                <div style="text-align: center; font-style: italic; color: #6b7280;">
                    Tidak ada realisasi anggaran.
                </div>
            @endforelse

        </div>

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : SERTIFIKAT --}}
        {{-- ========================================================================= --}}
        @if ($record->sertifikat)
            <div style="page-break-after: always;"></div>
            <div class="page-container"
                style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">SERTIFIKAT</h3>
                </div>
                <img src="{{ Storage::url($record->sertifikat) }}"
                    style="width: 100%; border: 1px solid #ccc; padding: 4px;">
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : BERITA ACARA --}}
        {{-- ========================================================================= --}}
        @if ($record->berita_acara)
            <div style="page-break-after: always;"></div>
            <div class="page-container"
                style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">BERITA ACARA</h3>
                </div>
                <img src="{{ Storage::url($record->berita_acara) }}"
                    style="width: 100%; border: 1px solid #ccc; padding: 4px;">
            </div>
        @endif

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LEMBAR PENGESAHAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div
                style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #111827; padding-bottom: 2rem;">
                <h1 style="font-size: 1.375rem; font-weight: 700; text-transform: uppercase; margin: 0;">
                    LEMBAR PENGESAHAN
                </h1>
                <h2 style="font-size: 1.125rem; font-weight: 700; text-transform: uppercase; margin: 0.75rem 0 0 0;">
                    PROPOSAL KEGIATAN
                </h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.75rem 0 0 0;">
                    {{ strtoupper($record->proposal->nama_kegiatan) }}
                </h3>
                <p style="font-size: 0.9375rem; margin: 0.75rem 0 0 0; color: #4b5563;">
                    MTsN 3 Tulungagung <br>
                    Tahun Pelajaran 2026/2027
                </p>
            </div>

            @if ($record->isPelatihMerangkapPembina())
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                            <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Disusun dan Diperiksa oleh,<br>
                                    Pelatih / Pembina Ekstrakurikuler
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->pelatih()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                                {!! $signatureImg($pelatihTtd, $pembinaApproved, -4) !!}

                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                            <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Disetujui oleh,<br>
                                    Wakil Kepala Madrasah<br>
                                    Bidang Kesiswaan
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->wakaKesiswaan()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                                {!! $signatureImg($wakaTtd, $wakaApproved, 4) !!}

                            </div>
                        </td>
                    </tr>
                </table>

                <table style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
                    <tr>
                        <td style="text-align:center; font-size:0.9375rem; line-height:1.5;">
                            <div style="position:relative;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Mengetahui,<br>
                                    Kepala MTsN 3 Tulungagung
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->kepalaMadrasah()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                                {!! $signatureImg($kepalaTtd, $kepalaApproved, -2) !!}

                            </div>
                        </td>
                    </tr>
                </table>

                {{-- Tanda Tangan: Mode Normal (4 Orang) --}}
            @else
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                            <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Disusun oleh,<br>
                                    Pelatih
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->pelatih()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                            <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Diperiksa oleh,<br>
                                    Pembina Ekstrakurikuler
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->pembina()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                                {!! $signatureImg($pembinaTtd, $pembinaApproved, 4) !!}

                            </div>
                        </td>
                    </tr>
                </table>

                <table style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                            <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Disetujui oleh,<br>
                                    Wakil Kepala Madrasah<br>
                                    Bidang Kesiswaan
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->wakaKesiswaan()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                                {!! $signatureImg($wakaTtd, $wakaApproved, -4) !!}

                            </div>
                        </td>
                        <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                            <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Mengetahui,<br>
                                    Kepala MTsN 3 Tulungagung
                                </div>

                                <div style="height:70px;"></div>

                                <div style="position:relative; z-index:1;">
                                    <strong style="text-decoration: underline;">
                                        {{ $record->kepalaMadrasah()?->pegawai?->nama ?? '........................................' }}
                                    </strong>
                                </div>

                                {!! $signatureImg($kepalaTtd, $kepalaApproved, 3) !!}

                            </div>
                        </td>
                    </tr>
                </table>
            @endif

        </div>

    </div>

</x-filament-panels::page>
