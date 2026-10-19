@php
    use App\Support\LaporanBulananHelper;

    $statistik = LaporanBulananHelper::statistik($record);

    // LOGIKA TAHUN PELAJARAN DINAMIS BERDASARKAN BULAN LAPORAN
    $bln = (int) $record->bulan;
    $thn = (int) $record->tahun;
    $tapelLaporan = $bln >= 7 ? $thn . '/' . ($thn + 1) : $thn - 1 . '/' . $thn;

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

<x-filament-panels::page>
    <style>
        /* Modern Responsive Document Preview */
        .main-wrapper {
            background: #f1f5f9;
            padding: 24px 12px;
            min-height: 100vh;
        }

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
                min-height: auto !important;
                border-radius: 6px;
            }

            .page-container.mt-30 {
                margin-top: 15px;
            }

            .signature-table td {
                display: block !important;
                width: 100% !important;
                padding: 0 !important;
                margin-bottom: 2rem !important;
            }
        }

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
                page-break-after: always;
            }

            body {
                background: white !important;
            }
        }
    </style>

    <div class="main-wrapper">

        {{-- Top Workflow Card --}}
        <div class="doc-header-card no-print">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; background: #ecfdf5; color: #047857; padding: 3px 10px; border-radius: 9999px;">
                            {{ $record->ekstrakurikuler->nama }}
                        </span>
                        <span style="font-size: 12px; color: #64748b;">
                            Periode: {{ \Carbon\Carbon::create()->month((int) $record->bulan)->translatedFormat('F') }} {{ $record->tahun }}
                        </span>
                    </div>
                    <h2 style="font-size: 17px; font-weight: 700; color: #0f172a; margin: 4px 0 0 0;">
                        Laporan Bulanan Ekstrakurikuler
                    </h2>
                    <div style="font-size: 13px; color: #64748b; margin-top: 3px;">
                        👥 {{ $statistik['jumlah_anggota'] }} Anggota Aktif &nbsp;•&nbsp; 📅 {{ $statistik['jumlah_sesi'] }} Sesi Latihan &nbsp;•&nbsp; 📊 Kehadiran {{ $statistik['persentase_kehadiran'] }}%
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
                <div class="step-item">
                    <div class="step-bubble {{ $currentStep > 1 ? 'done' : ($currentStep === 1 && ! $isRevision && ! $isRejected ? 'active' : ($isRevision ? 'active' : 'done')) }}">
                        @if ($currentStep > 1 || $record->status === 'approved') ✔ @else 1 @endif
                    </div>
                    <div class="step-label">Draft Laporan</div>
                </div>

                <div class="step-item">
                    <div class="step-bubble {{ $currentStep > 2 ? 'done' : ($currentStep === 2 ? 'active' : 'waiting') }}">
                        @if ($currentStep > 2) ✔ @else 2 @endif
                    </div>
                    <div class="step-label">Verifikasi Pembina</div>
                </div>

                <div class="step-item">
                    <div class="step-bubble {{ $currentStep > 3 ? 'done' : ($currentStep === 3 ? 'active' : 'waiting') }}">
                        @if ($currentStep > 3) ✔ @else 3 @endif
                    </div>
                    <div class="step-label">Waka Kesiswaan</div>
                </div>

                <div class="step-item">
                    <div class="step-bubble {{ $currentStep >= 5 ? 'done' : ($currentStep === 4 ? 'active' : 'waiting') }}">
                        @if ($currentStep >= 5) ✔ @else 4 @endif
                    </div>
                    <div class="step-label">Kepala Madrasah</div>
                </div>

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

        {{-- ========================= --}}
        {{-- COVER --}}
        {{-- ========================= --}}
        <div class="page-container">

            <div style="text-align:center;">

                @if (\App\Support\SettingHelper::schoolOfficialLogo())
                    <img src="{{ asset('storage/' . \App\Support\SettingHelper::schoolOfficialLogo()) }}"
                        style="display:block; width:110px; height:auto; margin:0 auto 20px auto;">
                @endif

                <div style="font-size:15px; font-weight:bold; text-transform:uppercase;">
                    KEMENTERIAN AGAMA REPUBLIK INDONESIA
                </div>

                <div style="font-size:26px; font-weight:bold; margin-top:6px;">
                    {{ \App\Support\SettingHelper::schoolName() }}
                </div>

                <div style="font-size:18px; margin-top:5px;">
                    Tahun Pelajaran {{ $tapelLaporan }}
                </div>

            </div>

            <hr style="margin:35px 0; border:1px solid #111827;">

            <div style="text-align:center;">

                <div style="font-size:28px; font-weight:bold; text-transform:uppercase;">
                    LAPORAN BULANAN
                </div>

                <div style="font-size:22px; margin-top:12px; font-weight:bold; text-transform:uppercase;">
                    <div style="font-size:24px; color:#111827;">
                        {{ $record->ekstrakurikuler->nama }}
                    </div>

                    <div style="font-size:18px; font-weight:600; color:#374151; margin-top:10px;">
                        Bulan {{ \Carbon\Carbon::create()->month((int) $record->bulan)->translatedFormat('F') }} {{ $record->tahun }}
                    </div>
                </div>

            </div>

        </div>

        {{-- BAB I --}}
        @include('filament.laporan-bulanan.preview.bab1', compact('record'))

        {{-- BAB II --}}
        @include('filament.laporan-bulanan.preview.bab2', compact('record'))

        {{-- BAB III --}}
        @include('filament.laporan-bulanan.preview.bab3', compact('record'))

        {{-- BAB IV --}}
        @include('filament.laporan-bulanan.preview.bab4', compact('record'))

        {{-- BAB V --}}
        @include('filament.laporan-bulanan.preview.bab5', compact('record'))

        {{-- Lampiran --}}
        @include('filament.laporan-bulanan.preview.lampiran', compact('record'))

        {{-- Pengesahan --}}
        @include('filament.laporan-bulanan.preview.pengesahan', compact('record'))

    </div>
</x-filament-panels::page>
