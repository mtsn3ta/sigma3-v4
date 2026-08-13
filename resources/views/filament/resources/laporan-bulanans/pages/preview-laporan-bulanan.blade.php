@php

    use App\Support\LaporanBulananHelper;

    $statistik = LaporanBulananHelper::statistik($record);

@endphp
<x-filament-panels::page>

    <div style="
    background:#e5e7eb;
    padding:40px 0;
">

        <div
            style="
        width:210mm;
        min-height:297mm;
        margin:0 auto;
        background:#ffffff;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
        font-family:Arial, Helvetica, sans-serif;
        color:#000;
        line-height:1.6;
    ">

            {{-- ========================= --}}
            {{-- COVER --}}
            {{-- ========================= --}}

            <div style="text-align:center;">

                @if (\App\Support\SettingHelper::schoolOfficialLogo())
                    <img src="{{ asset('storage/' . \App\Support\SettingHelper::schoolOfficialLogo()) }}"
                        style="
            display:block;
            width:110px;
            height:auto;
            margin:0 auto 20px auto;
        ">
                @endif

                <div
                    style="
                    font-size:15px;
                    font-weight:bold;
                    text-transform:uppercase;
                ">
                    KEMENTERIAN AGAMA REPUBLIK INDONESIA
                </div>

                <div style="
        font-size:26px;
        font-weight:bold;
        margin-top:6px;
    ">

                    {{ \App\Support\SettingHelper::schoolName() }}

                </div>

                <div style="
        font-size:18px;
        margin-top:5px;
    ">

                    Tahun Pelajaran
                    {{ \App\Support\SettingHelper::tahunAjaranLabel() }}

                </div>

            </div>

            <hr style="
            margin:35px 0;
            border:1px solid #000;
        ">

            <div style="text-align:center;">

                <div
                    style="
                    font-size:28px;
                    font-weight:bold;
                    text-transform:uppercase;
                ">
                    LAPORAN BULANAN
                </div>

                <div
                    style="
    font-size:22px;
    margin-top:12px;
    font-weight:bold;
    text-transform:uppercase;
">
                    <h2 class="text-xl">
                        {{ $record->ekstrakurikuler->nama }}
                    </h2>

                    <p>
                        {{-- TAMBAHKAN (int) SEBELUM $record->bulan --}}
                        {{ \Carbon\Carbon::create()->month((int) $record->bulan)->translatedFormat('F') }}
                        {{ $record->tahun }}
                    </p>
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

        @include('filament.laporan-bulanan.preview.pengesahan', compact('record'))

    </div>
</x-filament-panels::page>
