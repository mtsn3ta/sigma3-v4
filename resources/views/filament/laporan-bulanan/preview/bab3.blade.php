@php

    use Carbon\Carbon;

    $proposal = App\Support\LaporanBulananHelper::proposal($record);

    $kegiatan = App\Support\LaporanBulananHelper::kegiatan($record);

    $prestasi = App\Support\LaporanBulananHelper::prestasi($record);

@endphp

<div
    style="
        width:210mm;
        min-height:297mm;
        margin:30px auto;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
    ">

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

            BAB III
        </div>

        <div
            style="
                margin-top:10px;
                font-size:18px;
                font-weight:bold;
                text-transform:uppercase;
            ">

            KEGIATAN DAN PRESTASI EKSTRAKURIKULER

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- 3.1 Proposal --}}
    {{-- ========================================================= --}}

    <h4 style="margin-top:25px;">
        3.1 Proposal Kegiatan
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

                <th class="border" width="5%">No</th>

                <th class="border">Nama Kegiatan</th>

                <th class="border" width="20%">Jenis</th>

                <th class="border" width="15%">Tanggal</th>

                <th class="border" width="15%">Status</th>

            </tr>

        </thead>

        <tbody>

            @forelse($proposal as $item)
                <tr>

                    <td class="border" align="center">

                        {{ $loop->iteration }}

                    </td>

                    <td class="border">

                        {{ $item->nama_kegiatan }}

                    </td>

                    <td class="border">

                        {{ $item->jenis_kegiatan_label }}

                    </td>

                    <td class="border" align="center">

                        {{ Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}

                    </td>

                    <td class="border" align="center">

                        {{ ucfirst($item->status) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="border" align="center">

                        Tidak ada proposal kegiatan.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    {{-- ========================================================= --}}
    {{-- 3.2 Laporan Kegiatan --}}
    {{-- ========================================================= --}}

    <h4 style="margin-top:35px;">
        3.2 Laporan Kegiatan
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

                <th class="border" width="5%">No</th>

                <th class="border">Nama Kegiatan</th>

                <th class="border" width="20%">Tanggal</th>

                <th class="border" width="15%">Status</th>

            </tr>

        </thead>

        <tbody>

            @forelse($kegiatan as $item)
                <tr>

                    <td class="border" align="center">

                        {{ $loop->iteration }}

                    </td>

                    <td class="border">

                        {{ $item->proposal?->nama_kegiatan }}

                    </td>

                    <td class="border" align="center">

                        {{ Carbon::parse($item->proposal?->tanggal_mulai)->translatedFormat('d M Y') }}

                    </td>

                    <td class="border" align="center">

                        {{ ucfirst($item->status) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="border" align="center">

                        Tidak ada laporan kegiatan.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

    {{-- ========================================================= --}}
    {{-- 3.3 Prestasi --}}
    {{-- ========================================================= --}}

    <h4 style="margin-top:35px;">
        3.3 Prestasi
    </h4>

    <table
        style="
        width:100%;
        border-collapse:collapse;
        font-size:13px;
        margin-top:10px;
    ">

        <tr>

            <td class="border" align="center">

                Data prestasi belum tersedia.

            </td>

        </tr>

    </table>
</div>
