<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>

        Laporan Bulanan
        {{ $record->ekstrakurikuler->nama }}

    </title>

    <style>
        @page {
            margin: 20mm;
        }

        body {

            font-family: 'DejaVu Sans', sans-serif;

            font-size: 13px;

            line-height: 1.5;

        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-justify {
            text-align: justify;
        }

        .font-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
        }

        table {

            width: 100%;

            border-collapse: collapse;

        }

        .table-bordered th,
        .table-bordered td {

            border: 1px solid #000;

            padding: 7px;

        }

        .table-bordered th {

            background: #f3f4f6;

        }

        .logo {

            width: 110px;

        }

        .cover {

            min-height: 250mm;

        }
    </style>

</head>

<body>
    <div class="cover">

        <div class="text-center">

            @if (\App\Support\SettingHelper::schoolOfficialLogo())
                <img src="{{ public_path('storage/' . \App\Support\SettingHelper::schoolOfficialLogo()) }}"
                    class="logo">
            @endif
            <div class="font-bold uppercase">

                KEMENTERIAN AGAMA REPUBLIK INDONESIA

            </div>

            <div class="font-bold uppercase">

                KANTOR KEMENTERIAN AGAMA
                KABUPATEN TULUNGAGUNG

            </div>

            <div style="
        font-size:28px;
        font-weight:bold;
        margin-top:8px;
    ">

                {{ \App\Support\SettingHelper::schoolName() }}

            </div>

            <div style="margin-top:8px;">

                Tahun Pelajaran
                {{ \App\Support\SettingHelper::tahunAjaranLabel() }}

            </div>

            <hr style="margin:35px 0;">

            <div style="
font-size:28px;
font-weight:bold;
text-transform:uppercase;
">

                LAPORAN BULANAN

            </div>

            <div style="
font-size:22px;
font-weight:bold;
margin-top:10px;
">

                {{ strtoupper($record->ekstrakurikuler->nama) }}

            </div>

            <div style="margin-top:8px;">

                Bulan

                {{ \Carbon\Carbon::create()->month($record->bulan)->translatedFormat('F') }}

                {{ $record->tahun }}

            </div>

        </div>

    </div>

    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.bab1')
    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.bab2')
    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.bab3')
    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.bab4')
    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.bab5')
    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.lampiran')
    <div class="page-break"></div>
    @include('pdf.laporan-bulanan.pengesahan')
</body>

</html>
