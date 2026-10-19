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

        /* Rich Text Content */
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
                            {{ $record->proposal?->ekstrakurikuler?->nama ?? $record->ekstrakurikuler?->nama ?? 'Ekstrakurikuler' }}
                        </span>
                        <span style="font-size: 11px; font-weight: 600; background: #f0fdf4; color: #15803d; padding: 3px 8px; border-radius: 6px; border: 1px solid #bbf7d0;">
                            Laporan Kegiatan
                        </span>
                    </div>
                    <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 4px 0 0 0;">
                        {{ $record->proposal?->nama_kegiatan ?? 'Laporan Kegiatan' }}
                    </h2>
                    <div style="font-size: 13px; color: #64748b; margin-top: 3px;">
                        📍 {{ $record->proposal?->tempat ?? '-' }} &nbsp;•&nbsp; 
                        📅 {{ $record->proposal?->tanggal_mulai?->translatedFormat('d M Y') }} s.d. {{ $record->proposal?->tanggal_selesai?->translatedFormat('d M Y') }} &nbsp;•&nbsp; 
                        💰 Realisasi: <strong>Rp {{ number_format($record->total_realisasi, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <button onclick="window.print()" type="button"
                        style="cursor: pointer; background: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Laporan
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
                    <div class="step-label">Draft Laporan</div>
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

                {{-- Step 5: Selesai / Disetujui --}}
                <div class="step-item">
                    @if ($isRejected)
                        <div class="step-bubble rejected">✕</div>
                        <div class="step-label" style="color: #ef4444;">Ditolak</div>
                    @elseif ($isRevision)
                        <div class="step-bubble active">!</div>
                        <div class="step-label" style="color: #f59e0b;">Perlu Revisi</div>
                    @else
                        <div class="step-bubble {{ $record->status === 'approved' ? 'done' : 'waiting' }}">
                            @if ($record->status === 'approved') ✔ @else 5 @endif
                        </div>
                        <div class="step-label">Disetujui</div>
                    @endif
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
                <div style="font-size: 28px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em;">
                    LAPORAN KEGIATAN
                </div>
                <div style="font-size: 22px; margin-top: 12px; font-weight: bold; text-transform: uppercase; color: #1e293b;">
                    {{ $record->proposal?->nama_kegiatan }}
                </div>
            </div>

            <div style="margin-top: 50px;">
                <div class="table-responsive">
                    <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 16px; line-height: 1.8;">
                        <tr>
                            <td style="width: 220px;"><strong>Ekstrakurikuler</strong></td>
                            <td>: {{ $record->proposal?->ekstrakurikuler?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Kegiatan</strong></td>
                            <td>: {{ $record->proposal?->jenis_kegiatan_label ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tempat</strong></td>
                            <td>: {{ $record->proposal?->tempat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;"><strong>Tanggal Pelaksanaan</strong></td>
                            <td style="vertical-align: top;">
                                : {{ $record->proposal?->tanggal_mulai?->translatedFormat('d F Y') }} <br>
                                &nbsp;&nbsp;s.d. {{ $record->proposal?->tanggal_selesai?->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total Realisasi</strong></td>
                            <td>: <strong>Rp {{ number_format($record->total_realisasi, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Status Laporan</strong></td>
                            <td>: {{ $record->status_label }}</td>
                        </tr>
                    </table>
                </div>
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
                    {!! $record->proposal?->latar_belakang !!}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Tujuan</h4>
                <div class="rich-text-content">
                    {!! $record->proposal?->tujuan !!}
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">C. Identitas Kegiatan</h4>
                <div class="table-responsive">
                    <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 1rem; line-height: 1.6;">
                        <tr>
                            <td style="width: 220px; padding: 4px 0;">Nama Kegiatan</td>
                            <td style="padding: 4px 0;">: {{ $record->proposal?->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">Ekstrakurikuler</td>
                            <td style="padding: 4px 0;">: {{ $record->proposal?->ekstrakurikuler?->nama }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">Jenis Kegiatan</td>
                            <td style="padding: 4px 0;">: {{ $record->proposal?->jenis_kegiatan_label }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">Tempat</td>
                            <td style="padding: 4px 0;">: {{ $record->proposal?->tempat }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">Tanggal Pelaksanaan</td>
                            <td style="padding: 4px 0;">
                                : {{ $record->proposal?->tanggal_mulai?->translatedFormat('d F Y') }} s.d.
                                {{ $record->proposal?->tanggal_selesai?->translatedFormat('d F Y') }}
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
        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB II : PELAKSANAAN KEGIATAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB II</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PELAKSANAAN KEGIATAN</h3>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">A. Waktu dan Tempat</h4>
                <div class="table-responsive">
                    <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 1rem; line-height: 1.6;">
                        <tr>
                            <td style="width: 220px; padding: 4px 0;">Hari / Tanggal</td>
                            <td style="padding: 4px 0;">:
                                {{ $record->proposal?->tanggal_mulai?->translatedFormat('d F Y') }} s.d.
                                {{ $record->proposal?->tanggal_selesai?->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">Tempat</td>
                            <td style="padding: 4px 0;">: {{ $record->proposal?->tempat }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0;">Jenis Kegiatan</td>
                            <td style="padding: 4px 0;">: {{ $record->proposal?->jenis_kegiatan_label }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">B. Pendamping Kegiatan</h4>
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 50px; text-align: center;">No</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: left;">Nama</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 170px; text-align: center;">Peran</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 100px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($record->pendamping as $item)
                                <tr>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        {{ $loop->iteration }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: 500;">{{ $item->pegawai?->nama }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        {{ ucfirst(str_replace('_', ' ', $item->peran)) }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: {{ $item->status === 'hadir' ? '#ecfdf5; color: #047857' : ($item->status === 'izin' ? '#fef3c7; color: #b45309' : '#e0f2fe; color: #0369a1') }};">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="border: 1px solid #cbd5e1; padding: 12px; text-align: center; color: #64748b;">
                                        Tidak ada data pendamping.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">C. Peserta Kegiatan</h4>
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 50px; text-align: center;">No</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: left;">Nama</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 60px; text-align: center;">JK</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 120px; text-align: center;">Kelas</th>
                                <th style="border: 1px solid #cbd5e1; padding: 8px; width: 100px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($record->peserta as $item)
                                <tr>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        {{ $loop->iteration }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: 500;">{{ $item->siswa?->nama }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        {{ $item->siswa?->jenis_kelamin }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        {{ $item->siswa?->nama_kelas_aktif ?? '-' }}</td>
                                    <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; background: {{ $item->status === 'hadir' ? '#ecfdf5; color: #047857' : ($item->status === 'izin' ? '#fef3c7; color: #b45309' : ($item->status === 'sakit' ? '#e0f2fe; color: #0369a1' : '#fee2e2; color: #b91c1c')) }};">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="border: 1px solid #cbd5e1; padding: 12px; text-align: center; color: #64748b;">
                                        Tidak ada data peserta.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <h4 style="font-size: 1.125rem; font-weight: bold; margin-bottom: 1rem;">D. Rekap Kehadiran Peserta</h4>
                <div class="table-responsive">
                    <table style="width: 100%; max-width: 400px; border-collapse: collapse; font-size: 0.875rem;">
                        <tr>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; background: #f8fafc; font-weight: 500;">Hadir</td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; width: 100px; font-weight: 700; color: #047857;">
                                {{ $record->peserta->where('status', 'hadir')->count() }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; background: #f8fafc; font-weight: 500;">Izin</td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: 700; color: #b45309;">
                                {{ $record->peserta->where('status', 'izin')->count() }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; background: #f8fafc; font-weight: 500;">Sakit</td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: 700; color: #0369a1;">
                                {{ $record->peserta->where('status', 'sakit')->count() }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; background: #f8fafc; font-weight: 500;">Alpha</td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: 700; color: #b91c1c;">
                                {{ $record->peserta->where('status', 'alpha')->count() }}</td>
                        </tr>
                    </table>
                </div>
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
        <div class="page-container mt-30">

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
                    {{ !empty(strip_tags($record->prestasi ?? '')) ? 'C' : 'B' }}. Evaluasi & Saran</h4>
                <div class="rich-text-content">
                    {!! $record->evaluasi !!}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB IV : REALISASI ANGGARAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB IV</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">REALISASI ANGGARAN</h3>
            </div>

            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="border: 1px solid #cbd5e1; padding: 8px; width: 40px; text-align: center;">No</th>
                            <th style="border: 1px solid #cbd5e1; padding: 8px; text-align: left;">Uraian Kebutuhan</th>
                            <th style="border: 1px solid #cbd5e1; padding: 8px; width: 130px; text-align: right;">Proposal</th>
                            <th style="border: 1px solid #cbd5e1; padding: 8px; width: 130px; text-align: right;">Realisasi</th>
                            <th style="border: 1px solid #cbd5e1; padding: 8px; width: 130px; text-align: right;">Selisih</th>
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
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">
                                    {{ $loop->iteration }}</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: 500;">{{ $item->uraian }}</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: right;">Rp
                                    {{ number_format($proposal, 0, ',', '.') }}</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: right; font-weight: 600; color: #047857;">Rp
                                    {{ number_format($realisasi, 0, ',', '.') }}</td>
                                <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: right; color: {{ $selisih < 0 ? '#b91c1c' : '#475569' }};">Rp
                                    {{ number_format($selisih, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    style="border: 1px solid #cbd5e1; padding: 16px; text-align: center; font-style: italic; color: #64748b;">
                                    Tidak ada data realisasi anggaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="background: #f1f5f9; font-weight: bold;">
                            <td colspan="2" style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">TOTAL
                            </td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: right;">Rp
                                {{ number_format($totalProposal, 0, ',', '.') }}</td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: right; color: #047857;">Rp
                                {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                            <td style="border: 1px solid #cbd5e1; padding: 8px; text-align: right;">Rp
                                {{ number_format($totalProposal - $totalRealisasi, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- BAB V : PENUTUP --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">BAB V</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">PENUTUP</h3>
            </div>

            <div style="margin-bottom: 2rem;">
                <div class="rich-text-content">
                    {!! $record->penutup ?? '<p>Demikian laporan pertanggungjawaban kegiatan ini kami susun dengan sebenar-benarnya sebagai bahan evaluasi dan pertanggungjawaban pelaksanaan kegiatan ekstrakurikuler. Atas perhatian, dukungan, dan kerja sama seluruh pihak yang terlibat, kami mengucapkan terima kasih.</p>' !!}
                </div>
            </div>

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : DOKUMENTASI --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">DOKUMENTASI KEGIATAN</h3>
            </div>

            @if (!empty($record->dokumentasi))
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px;">
                    @foreach ($record->dokumentasi as $foto)
                        <div style="border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #f8fafc; padding: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                            <img src="{{ Storage::url($foto) }}"
                                style="width: 100%; height: 200px; object-fit: cover; border-radius: 6px; display: block;"
                                alt="Dokumentasi">
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; font-style: italic; color: #64748b; margin-top: 40px;">
                    Tidak ada lampiran dokumentasi foto.
                </div>
            @endif

        </div>

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : BUKTI TRANSAKSI --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">

            <div style="text-align: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">BUKTI NOTA / TRANSAKSI</h3>
            </div>

            @forelse($record->realisasiAnggaran as $item)
                <div style="margin-bottom: 25px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 20px;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; margin-bottom: 8px; color: #1e293b;">
                        {{ $loop->iteration }}. {{ $item->uraian }} (Rp {{ number_format($item->subtotal, 0, ',', '.') }})
                    </h4>
                    @if ($item->bukti)
                        <div style="border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; padding: 6px; background: #f8fafc; display: inline-block; max-width: 100%;">
                            <img src="{{ Storage::url($item->bukti) }}"
                                style="max-width: 100%; max-height: 400px; object-fit: contain; border-radius: 4px; display: block;">
                        </div>
                    @else
                        <p style="font-style: italic; color: #94a3b8; font-size: 13px;">Tidak ada bukti nota / struk.</p>
                    @endif
                </div>
            @empty
                <div style="text-align: center; font-style: italic; color: #64748b;">
                    Tidak ada data realisasi anggaran.
                </div>
            @endforelse

        </div>

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : SERTIFIKAT --}}
        {{-- ========================================================================= --}}
        @if ($record->sertifikat)
            <div style="page-break-after: always;"></div>
            <div class="page-container mt-30">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">SERTIFIKAT / PIAGAM</h3>
                </div>
                <div style="text-align: center;">
                    <img src="{{ Storage::url($record->sertifikat) }}"
                        style="max-width: 100%; max-height: 700px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px;">
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- LAMPIRAN : BERITA ACARA --}}
        {{-- ========================================================================= --}}
        @if ($record->berita_acara)
            <div style="page-break-after: always;"></div>
            <div class="page-container mt-30">
                <div style="text-align: center; margin-bottom: 2.5rem;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; margin: 0;">LAMPIRAN</h2>
                    <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.25rem 0 0 0;">BERITA ACARA</h3>
                </div>
                <div style="text-align: center;">
                    <img src="{{ Storage::url($record->berita_acara) }}"
                        style="max-width: 100%; max-height: 700px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px;">
                </div>
            </div>
        @endif

        <div style="page-break-after: always;"></div>

        {{-- ========================================================================= --}}
        {{-- LEMBAR PENGESAHAN --}}
        {{-- ========================================================================= --}}
        <div class="page-container mt-30">

            <div
                style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #111827; padding-bottom: 2rem;">
                <h1 style="font-size: 1.375rem; font-weight: 700; text-transform: uppercase; margin: 0;">
                    LEMBAR PENGESAHAN
                </h1>
                <h2 style="font-size: 1.125rem; font-weight: 700; text-transform: uppercase; margin: 0.75rem 0 0 0;">
                    LAPORAN KEGIATAN
                </h2>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.75rem 0 0 0;">
                    {{ strtoupper($record->proposal?->nama_kegiatan ?? 'LAPORAN KEGIATAN') }}
                </h3>
                <p style="font-size: 0.9375rem; margin: 0.75rem 0 0 0; color: #4b5563;">
                    {{ \App\Support\SettingHelper::schoolName() }} <br>
                    Tahun Pelajaran {{ \App\Support\SettingHelper::tahunAjaranLabel() }}
                </p>
            </div>

            @if ($record->isPelatihMerangkapPembina())
                <table class="signature-table" style="width: 100%; border-collapse: collapse;">
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

                <table class="signature-table" style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
                    <tr>
                        <td style="text-align:center; font-size:0.9375rem; line-height:1.5;">
                            <div style="position:relative;">

                                <div
                                    style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                                    Mengetahui,<br>
                                    Kepala {{ \App\Support\SettingHelper::schoolName() }}
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
                <table class="signature-table" style="width: 100%; border-collapse: collapse;">
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

                <table class="signature-table" style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
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
                                    Kepala {{ \App\Support\SettingHelper::schoolName() }}
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
