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
    <style>
        /* Modern Document Preview Styles */
        .main-wrapper {
            background: #f1f5f9;
            padding: 24px 12px;
            min-height: 100vh;
        }

        /* Top Document Workflow Bar */
        .doc-header-card {
            max-width: 210mm;
            margin: 0 auto 20px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 18px 24px;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.07);
            border: 1px solid #e2e8f0;
        }

        .stepper-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin-top: 16px;
            overflow-x: auto;
            padding-bottom: 6px;
            gap: 8px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-width: 90px;
            flex: 1;
            position: relative;
        }

        .step-bubble {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 6px;
            transition: all 0.2s;
            z-index: 2;
        }

        .step-bubble.done {
            background: #10b981;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.35);
        }

        .step-bubble.active {
            background: #f59e0b;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.2);
            animation: pulse 2s infinite;
        }

        .step-bubble.waiting {
            background: #e2e8f0;
            color: #64748b;
        }

        .step-bubble.rejected {
            background: #ef4444;
            color: #ffffff;
        }

        .step-label {
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            line-height: 1.3;
        }

        /* Sheet Kertas A4 */
        .page-container {
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 24mm;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            color: #111827;
            line-height: 1.6;
            border-radius: 4px;
        }

        .page-container.mt-30 {
            margin-top: 25px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        /* Rich Text Formatting */
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

        /* Responsive Mobile Adjustments */
        @media screen and (max-width: 768px) {
            .main-wrapper {
                padding: 12px 6px;
            }

            .doc-header-card {
                padding: 14px 16px;
                border-radius: 10px;
                margin-bottom: 15px;
            }

            .page-container {
                padding: 16px 14px !important;
                min-height: auto;
                border-radius: 6px;
            }

            .page-container.mt-30 {
                margin-top: 15px;
            }

            .info-table td {
                display: block !important;
                width: 100% !important;
                padding: 3px 0 !important;
            }

            .signature-table td {
                display: block !important;
                width: 100% !important;
                margin-bottom: 2rem !important;
            }
        }

        /* Print Media Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .main-wrapper {
                padding: 0 !important;
                background: transparent !important;
            }

            .page-container {
                width: 210mm !important;
                max-width: none !important;
                min-height: 297mm !important;
                margin: 0 !important;
                box-shadow: none !important;
                padding: 20mm !important;
                border-radius: 0 !important;
            }

            body {
                background: white !important;
            }
        }
    </style>

    <div class="main-wrapper">

        {{-- ========================================================================= --}}
        {{-- TOP WORKFLOW / STATUS CARD (SCREEN ONLY) --}}
        {{-- ========================================================================= --}}
        @php
            $statusOrder = [
                'draft' => 1,
                'pending_pembina' => 2,
                'pending_waka' => 3,
                'pending_kepala' => 4,
                'approved' => 5,
            ];
            $currentStep = $statusOrder[$record->status] ?? 1;
            $isRejected = $record->status === 'rejected';
            $isRevision = $record->status === 'revision';
        @endphp

        <div class="doc-header-card no-print">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; background: #ecfdf5; color: #047857; padding: 3px 10px; border-radius: 9999px;">
                            {{ $record->ekstrakurikuler->nama }}
                        </span>
                        <span style="font-size: 12px; color: #64748b;">
                            {{ $record->jenis_kegiatan_label }}
                        </span>
                    </div>
                    <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 4px 0 0 0;">
                        {{ $record->nama_kegiatan }}
                    </h2>
                    <div style="font-size: 13px; color: #64748b; margin-top: 3px;">
                        📍 {{ $record->tempat }} &nbsp;•&nbsp; 💰 Rp {{ number_format($record->total_anggaran, 0, ',', '.') }}
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <button onclick="window.print()" type="button"
                        style="cursor: pointer; background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Lembar
                    </button>
                </div>
            </div>

            {{-- Stepper Progress Bar --}}
            <div class="stepper-container">
                {{-- Step 1: Draft --}}
                <div class="step-item">
                    <div class="step-bubble {{ $currentStep > 1 ? 'done' : ($currentStep === 1 && ! $isRevision && ! $isRejected ? 'active' : ($isRevision ? 'active' : 'done')) }}">
                        @if ($currentStep > 1 || $record->status === 'approved') ✔ @else 1 @endif
                    </div>
                    <div class="step-label">Draft Proposal</div>
                </div>

                {{-- Step 2: Pembina --}}
                <div class="step-item">
                    <div class="step-bubble {{ $currentStep > 2 ? 'done' : ($currentStep === 2 ? 'active' : 'waiting') }}">
                        @if ($currentStep > 2) ✔ @else 2 @endif
                    </div>
                    <div class="step-label">Verifikasi Pembina</div>
                </div>

                {{-- Step 3: Waka Kesiswaan --}}
                <div class="step-item">
                    <div class="step-bubble {{ $currentStep > 3 ? 'done' : ($currentStep === 3 ? 'active' : 'waiting') }}">
                        @if ($currentStep > 3) ✔ @else 3 @endif
                    </div>
                    <div class="step-label">Waka Kesiswaan</div>
                </div>

                {{-- Step 4: Kepala Madrasah --}}
                <div class="step-item">
                    <div class="step-bubble {{ $currentStep >= 5 ? 'done' : ($currentStep === 4 ? 'active' : 'waiting') }}">
                        @if ($currentStep >= 5) ✔ @else 4 @endif
                    </div>
                    <div class="step-label">Kepala Madrasah</div>
                </div>

                {{-- Step 5: Status Akhir --}}
                <div class="step-item">
                    <div class="step-bubble {{ $record->status === 'approved' ? 'done' : ($isRejected ? 'rejected' : 'waiting') }}">
                        @if ($record->status === 'approved') 🏆 @elseif($isRejected) ✖ @else 5 @endif
                    </div>
                    <div class="step-label">
                        @if ($record->status === 'approved')
                            Disetujui
                        @elseif($isRejected)
                            Ditolak
                        @elseif($isRevision)
                            Revisi
                        @else
                            Selesai
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- HALAMAN COVER --}}
        {{-- ========================================================================= --}}
        <div class="page-container">
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
                <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 16px; line-height: 1.8;">
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
                            @php
                                $tglMulai = $record->tanggal_mulai?->translatedFormat('d F Y');
                                $tglSelesai = $record->tanggal_selesai?->translatedFormat('d F Y');
                            @endphp

                            : {{ $tglMulai }}

                            @if ($tglSelesai && $tglSelesai !== $tglMulai)
                                <br>
                                &nbsp;&nbsp;s.d. {{ $tglSelesai }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Proposal</strong></td>
                        <td>: {{ $record->status_label }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB I : PENDAHULUAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">
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
        <div class="page-container mt-30">
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
        <div class="page-container mt-30">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB III</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PESERTA DAN PENDAMPING</h3>
            </div>

            <div style="margin-bottom: 2.5rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Peserta Kegiatan</h4>
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead style="background: #f3f4f6;">
                            <tr>
                                <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No
                                </th>
                                <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Nama Peserta</th>
                                <th style="border: 1px solid #111827; padding: 8px; width: 80px; text-align: center;">JK
                                </th>
                                <th style="border: 1px solid #111827; padding: 8px; width: 120px; text-align: center;">
                                    Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($record->peserta as $peserta)
                                <tr>
                                    <td style="border: 1px solid #111827; padding: 8px; text-align: center;">
                                        {{ $loop->iteration }}</td>
                                    <td style="border: 1px solid #111827; padding: 8px;">{{ $peserta->siswa->nama }}
                                    </td>
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
            </div>

            @if ($record->pendamping->count())
                <div style="margin-bottom: 2rem;">
                    <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Pendamping Kegiatan</h4>
                    <div class="table-responsive">
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                            <thead style="background: #f3f4f6;">
                                <tr>
                                    <th
                                        style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">
                                        No</th>
                                    <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Nama
                                        Pendamping</th>
                                    <th
                                        style="border: 1px solid #111827; padding: 8px; width: 180px; text-align: center;">
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
                </div>
            @endif
        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB IV : RENCANA ANGGARAN BIAYA --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">
            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB IV</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">RENCANA ANGGARAN BIAYA</h3>
            </div>

            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f3f4f6;">
                        <tr>
                            <th style="border: 1px solid #111827; padding: 8px; width: 50px; text-align: center;">No
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; text-align: left;">Uraian</th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 70px; text-align: center;">Qty
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 90px; text-align: center;">Satuan
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 140px; text-align: right;">Harga
                            </th>
                            <th style="border: 1px solid #111827; padding: 8px; width: 150px; text-align: right;">
                                Subtotal</th>
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
            </div>

            <div
                style="margin-top: 2rem; padding: 1.5rem; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                <p style="margin: 0; text-align: justify; line-height: 1.6; font-size: 0.9375rem;">
                    Berdasarkan rincian Rencana Anggaran Biaya (RAB) di atas, total dana yang diperlukan untuk
                    melaksanakan kegiatan <strong>{{ $record->nama_kegiatan }}</strong> adalah sebesar:
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
        <div class="page-container mt-30">

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

            <table class="signature-table" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding: 0 0.5rem;">
                        <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                            <div
                                style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                Disusun oleh,<br>
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
                    <td style="width: 50%; vertical-align: top; padding: 0 0.5rem;">
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

            <table class="signature-table" style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
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

        </div>
    </div>
</x-filament-panels::page>
