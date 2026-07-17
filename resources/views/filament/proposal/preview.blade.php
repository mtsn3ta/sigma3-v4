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
                        alt="Logo Madrasah" style="display: block; width: 110px; height: auto; margin: 0 auto 20px auto;">
                @endif

                <div style="font-size: 15px; font-weight: bold; text-transform: uppercase;">
                    KEMENTERIAN AGAMA REPUBLIK INDONESIA
                </div>

                <div style="font-size: 15px; font-weight: bold; text-transform: uppercase;">
                    KANTOR KEMENTERIAN AGAMA KABUPATEN TULUNGAGUNG
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
                    PROPOSAL KEGIATAN
                </div>
                <div style="font-size: 22px; margin-top: 12px; font-weight: bold; text-transform: uppercase;">
                    {{ $record->nama_kegiatan }}
                </div>
            </div>

            <div style="margin-top: 60px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 16px; line-height: 1.8;">
                    <tr>
                        <td style="width: 220px;"><strong>Ekstrakurikuler</strong></td>
                        <td>: {{ $record->ekstrakurikuler->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Kegiatan</strong></td>
                        <td>: {{ $record->jenis_kegiatan_label }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tempat</strong></td>
                        <td>: {{ $record->tempat }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;"><strong>Tanggal</strong></td>
                        <td style="vertical-align: top;">
                            : {{ $record->tanggal_mulai?->translatedFormat('d F Y') }} <br>
                            &nbsp;&nbsp;s.d. {{ $record->tanggal_selesai?->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Proposal</strong></td>
                        <td>: {{ $record->status_label }}</td>
                    </tr>
                </table>
            </div>

            <div
                style="margin-top: 100px; text-align: center; font-size: 18px; font-weight: bold; text-transform: uppercase;">
                MTsN 3 Tulungagung
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB I : PENDAHULUAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto 0; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB I</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PENDAHULUAN</h3>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Latar Belakang</h4>
                <div class="rich-text-content">
                    {!! $record->latar_belakang !!}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Dasar Kegiatan</h4>
                <div class="rich-text-content">
                    {!! $record->dasar_kegiatan !!}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB II : TUJUAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto 0; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB II</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">TUJUAN</h3>
            </div>

            <div class="rich-text-content">
                {!! $record->tujuan !!}
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB III : PESERTA KEGIATAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto 0; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB III</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PESERTA DAN PENDAMPING</h3>
            </div>

            <div style="margin-bottom: 2.5rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Peserta Kegiatan</h4>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Nama Peserta</th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 80px; text-align: center;">JK
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 120px; text-align: center;">Kelas
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($record->peserta as $peserta)
                            <tr>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #111827; padding: 8px;">{{ $peserta->siswa->nama }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $peserta->siswa->jenis_kelamin }}</td>
                                <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                    {{ $peserta->siswa->kelasAktif?->kelas?->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    style="border: 1px solid #111827; padding: 12px; text-align: center; font-style: italic; color: #6b7280;">
                                    Belum ada peserta.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($record->pendamping->count())
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Pendamping Kegiatan</h4>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead style="background: #f3f4f6;">
                            <tr>
                                <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No
                                </th>
                                <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Nama Pendamping
                                </th>
                                <th style="border: 1px solid #111827; padding: 8px; width: 180px; text-align: center;">
                                    Peran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($record->pendamping as $pendamping)
                                <tr>
                                    <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                        {{ $loop->iteration }}</td>
                                    <td style="border: 1px solid #111827; padding: 8px;">
                                        {{ $pendamping->pegawai->nama }}</td>
                                    <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                        {{ match ($pendamping->peran) {
                                            'pelatih' => 'Pelatih',
                                            'pembina' => 'Pembina',
                                            'guru_pendamping' => 'Guru Pendamping',
                                            'official' => 'Official',
                                            'medis' => 'Medis',
                                            'transportasi' => 'Transportasi',
                                            default => 'Lainnya',
                                        } }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB IV : RENCANA ANGGARAN BIAYA --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto 0; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB IV</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">RENCANA ANGGARAN BIAYA</h3>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead style="background: #f3f4f6;">
                    <tr>
                        <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No</th>
                        <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Uraian</th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 70px; text-align: center;">Qty</th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 90px; text-align: center;">Satuan
                        </th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 140px; text-align: right;">Harga</th>
                        <th style="border: 1px solid #111827; padding: 8px; width: 150px; text-align: right;">Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($record->rabs as $rab)
                        <tr>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                {{ $loop->iteration }}</td>
                            <td style="border: 1px solid #111827; padding: 8px;">{{ $rab->uraian }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                {{ $rab->qty }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                {{ $rab->satuan }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                                {{ number_format($rab->harga_satuan, 0, ',', '.') }}</td>
                            <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                                {{ number_format($rab->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                style="border: 1px solid #111827; padding: 12px; text-align: center; font-style: italic; color: #6b7280;">
                                Belum ada anggaran yang direncanakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #f3f4f6; font-weight: bold;">
                        <td colspan="5" style="border: 1px solid #111827; padding: 8px; text-align: right;">TOTAL
                            KESELURUHAN</td>
                        <td style="border: 1px solid #111827; padding: 8px; text-align: right;">Rp
                            {{ number_format($record->total_anggaran, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div
                style="margin-top: 2rem; padding: 1.5rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                <p style="margin: 0; text-align: justify; line-height: 1.6; font-size: 0.9375rem;">
                    Berdasarkan rincian Rencana Anggaran Biaya (RAB) di atas, total dana yang diperlukan untuk
                    melaksanakan kegiatan
                    <strong>{{ $record->nama_kegiatan }}</strong> adalah sebesar:
                </p>

                <div
                    style="margin: 1.5rem 0; text-align: center; font-size: 1.5rem; font-weight: bold; color: #111827;">
                    Rp {{ number_format($record->total_anggaran, 0, ',', '.') }}
                </div>

                <p style="margin: 0; text-align: justify; line-height: 1.6; font-size: 0.9375rem;">
                    Anggaran tersebut akan digunakan sebagai acuan dalam pelaksanaan kegiatan sesuai kebutuhan dan
                    ketentuan yang berlaku.
                </p>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LEMBAR PENGESAHAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container"
            style="width: 210mm; min-height: 297mm; margin: 30px auto 0; background: #ffffff; padding: 25mm; box-shadow: 0 4px 20px rgba(0,0,0,.08); box-sizing: border-box; font-family: Arial, sans-serif; color: #111827;">

            <div
                style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #111827; padding-bottom: 2rem;">
                <h1
                    style="font-size: 1.375rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
                    LEMBAR PENGESAHAN
                </h1>
                <h2
                    style="font-size: 1.125rem; font-weight: 700; text-transform: uppercase; margin: 0.75rem 0 0 0; color: #374151;">
                    PROPOSAL KEGIATAN
                </h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.75rem 0 0 0; color: #111827;">
                    {{ strtoupper($record->nama_kegiatan) }}
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
