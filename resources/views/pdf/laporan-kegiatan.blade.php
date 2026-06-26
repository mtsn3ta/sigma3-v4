<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Proposal {{ $record->nama_kegiatan }}</title>
    <style>
        @page {
            margin: 20mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #000;
            line-height: 1.5;
        }

        /* --- UTILITY CLASSES --- */
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

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mt-30 {
            margin-top: 30px;
        }

        .mt-40 {
            margin-top: 40px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .page-break {
            page-break-after: always;
        }

        .indent {
            text-indent: 40px;
        }

        /* --- TYPOGRAPHY --- */
        h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 25px 0;
            text-align: center;
        }

        h3 {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 30px 0;
            text-align: center;
        }

        h4 {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }

        /* --- COVER --- */
        .cover-container {
            width: 100%;
            min-height: 250mm;
            padding-top: 20px;
        }

        .logo {
            width: 110px;
            height: auto;
            margin-bottom: 20px;
        }

        .kop-instansi {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-nama {
            font-size: 26px;
            font-weight: bold;
            margin-top: 6px;
            text-transform: uppercase;
        }

        .kop-tahun {
            font-size: 18px;
            margin-top: 5px;
        }

        .garis-kop {
            margin: 35px 0;
            border: 1px solid #000;
        }

        .judul-proposal {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .nama-kegiatan {
            font-size: 22px;
            margin-top: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .cover-footer {
            margin-top: 120px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* --- TABLES --- */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 4px 0;
            font-size: 15px;
            vertical-align: top;
        }

        .table-info td:first-child {
            width: 220px;
            font-weight: bold;
        }

        .table-bordered {
            font-size: 13px;
            margin-top: 20px;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000;
            padding: 8px;
        }

        .table-bordered th {
            background: #f3f4f6;
            text-align: center;
            font-weight: bold;
        }

        .table-bordered tbody td {
            vertical-align: middle;
        }

        /* --- SIGNATURES --- */
        .table-ttd {
            width: 100%;
            text-align: center;
            font-size: 14px;
            margin-top: 40px;
            border: none;
        }

        .table-ttd td {
            border: none;
            padding: 0;
        }

        .ttd-space {
            height: 90px;
        }
    </style>
</head>

<body>
    {{-- ========================= COVER ========================= --}}

    <div class="cover-container">

        <div class="text-center">

            <img src="{{ public_path('images/logo-mtsn3.png') }}" class="logo">

            <div class="kop-instansi">
                KEMENTERIAN AGAMA REPUBLIK INDONESIA
            </div>

            <div class="kop-instansi">
                KANTOR KEMENTERIAN AGAMA KABUPATEN TULUNGAGUNG
            </div>

            <div class="kop-nama">
                MTsN 3 TULUNGAGUNG
            </div>

            <div class="kop-tahun">
                Tahun Pelajaran 2026/2027
            </div>

        </div>

        <hr class="garis-kop">

        <div class="text-center">

            <div class="judul-proposal">

                LAPORAN KEGIATAN

            </div>

            <div class="nama-kegiatan">

                {{ $record->proposal->nama_kegiatan }}

            </div>

        </div>

        <div class="mt-40">

            <table class="table-info">

                <tr>

                    <td>Ekstrakurikuler</td>

                    <td>

                        :

                        {{ $record->proposal->ekstrakurikuler->nama }}

                    </td>

                </tr>

                <tr>

                    <td>Jenis Kegiatan</td>

                    <td>

                        :

                        {{ $record->proposal->jenis_kegiatan_label }}

                    </td>

                </tr>

                <tr>

                    <td>Tempat</td>

                    <td>

                        :

                        {{ $record->proposal->tempat }}

                    </td>

                </tr>

                <tr>

                    <td>Tanggal</td>

                    <td>

                        :

                        {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }}

                        s.d.

                        {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}

                    </td>

                </tr>

                <tr>

                    <td>Status Laporan</td>

                    <td>

                        :

                        {{ ucfirst($record->status) }}

                    </td>

                </tr>

            </table>

        </div>

        <div class="cover-footer">

            MTsN 3 Tulungagung

        </div>

    </div>

    <div class="page-break"></div>
    {{-- ========================= BAB I ========================= --}}

    <div>

        <h2>BAB I</h2>

        <h3>PENDAHULUAN</h3>

        {{-- LATAR BELAKANG --}}

        <h4>A. Latar Belakang</h4>

        <div class="text-justify mt-10">

            {!! $record->proposal->latar_belakang !!}

        </div>

        {{-- TUJUAN --}}

        <h4>B. Tujuan</h4>

        <div class="text-justify mt-10">

            {!! $record->proposal->tujuan !!}

        </div>

        {{-- IDENTITAS KEGIATAN --}}

        <h4>C. Identitas Kegiatan</h4>

        <table class="table-info mt-20">

            <tr>

                <td>Nama Kegiatan</td>

                <td>

                    :

                    {{ $record->proposal->nama_kegiatan }}

                </td>

            </tr>

            <tr>

                <td>Ekstrakurikuler</td>

                <td>

                    :

                    {{ $record->proposal->ekstrakurikuler->nama }}

                </td>

            </tr>

            <tr>

                <td>Jenis Kegiatan</td>

                <td>

                    :

                    {{ $record->proposal->jenis_kegiatan_label }}

                </td>

            </tr>

            <tr>

                <td>Tempat</td>

                <td>

                    :

                    {{ $record->proposal->tempat }}

                </td>

            </tr>

            <tr>

                <td>Tanggal Pelaksanaan</td>

                <td>

                    :

                    {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }}

                    s.d.

                    {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}

                </td>

            </tr>

            <tr>

                <td>Jumlah Peserta</td>

                <td>

                    :

                    {{ $record->peserta->count() }} Orang

                </td>

            </tr>

            <tr>

                <td>Jumlah Pendamping</td>

                <td>

                    :

                    {{ $record->pendamping->count() }} Orang

                </td>

            </tr>

        </table>

    </div>

    <div class="page-break"></div>
    {{-- ========================= BAB II ========================= --}}

    <div>

        <h2>BAB II</h2>

        <h3>PELAKSANAAN KEGIATAN</h3>

        <h4>A. Waktu Pelaksanaan</h4>

        <table class="table-info">

            <tr>

                <td>Tanggal</td>

                <td>

                    :

                    {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }}

                    s.d.

                    {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}

                </td>

            </tr>

        </table>

        <h4>B. Tempat Pelaksanaan</h4>

        <div class="text-justify">

            {{ $record->proposal->tempat }}

        </div>

        <h4>C. Pendamping Kegiatan</h4>

        <table class="table-bordered">

            <thead>

                <tr>

                    <th style="width:50px;">No</th>

                    <th>Nama</th>

                    <th style="width:170px;">Peran</th>

                    <th style="width:100px;">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($record->pendamping as $pendamping)
                    <tr>

                        <td class="text-center">

                            {{ $loop->iteration }}

                        </td>

                        <td>

                            {{ $pendamping->pegawai->nama }}

                        </td>

                        <td class="text-center">

                            {{ ucfirst(str_replace('_', ' ', $pendamping->peran)) }}

                        </td>

                        <td class="text-center">

                            {{ ucfirst($pendamping->status) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center">

                            Tidak ada pendamping.

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

        <h4>D. Peserta Kegiatan</h4>

        <table class="table-bordered">

            <thead>

                <tr>

                    <th style="width:50px;">No</th>

                    <th>Nama</th>

                    <th style="width:80px;">JK</th>

                    <th style="width:120px;">Kelas</th>

                    <th style="width:90px;">Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($record->peserta as $peserta)
                    <tr>

                        <td class="text-center">

                            {{ $loop->iteration }}

                        </td>

                        <td>

                            {{ $peserta->siswa->nama }}

                        </td>

                        <td class="text-center">

                            {{ $peserta->siswa->jenis_kelamin }}

                        </td>

                        <td class="text-center">

                            {{ $peserta->siswa->kelasAktif?->kelas?->nama ?? '-' }}

                        </td>

                        <td class="text-center">

                            {{ ucfirst($peserta->status) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center">

                            Tidak ada peserta.

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>

        <h4>E. Rekap Kehadiran</h4>

        <table class="table-bordered">

            <thead>

                <tr>

                    <th>Status</th>

                    <th>Jumlah</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>Hadir</td>

                    <td class="text-center">

                        {{ $record->peserta->where('status', 'hadir')->count() }}

                    </td>

                </tr>

                <tr>

                    <td>Izin</td>

                    <td class="text-center">

                        {{ $record->peserta->where('status', 'izin')->count() }}

                    </td>

                </tr>

                <tr>

                    <td>Sakit</td>

                    <td class="text-center">

                        {{ $record->peserta->where('status', 'sakit')->count() }}

                    </td>

                </tr>

                <tr>

                    <td>Alpha</td>

                    <td class="text-center">

                        {{ $record->peserta->where('status', 'alpha')->count() }}

                    </td>

                </tr>

            </tbody>

        </table>

        <h4>F. Uraian Pelaksanaan</h4>

        <div class="text-justify mt-10">

            {!! $record->uraian_pelaksanaan !!}

        </div>

    </div>

    <div class="page-break"></div>
    {{-- ========================= BAB III ========================= --}}

    <div>

        <h2>BAB III</h2>

        <h3>HASIL KEGIATAN</h3>

        <h4>A. Pencapaian</h4>

        <div class="text-justify mt-10">

            {!! $record->pencapaian !!}

        </div>

        @if (!empty(strip_tags($record->prestasi ?? '')))
            <h4>B. Prestasi</h4>

            <div class="text-justify mt-10">

                {!! $record->prestasi !!}

            </div>

            <h4>C. Evaluasi</h4>
        @else
            <h4>B. Evaluasi</h4>
        @endif

        <div class="text-justify mt-10">

            {!! $record->evaluasi !!}

        </div>

    </div>

    <div class="page-break"></div>
    {{-- ========================= BAB IV ========================= --}}

    <div>

        <h2>BAB IV</h2>

        <h3>REALISASI ANGGARAN</h3>

        <table class="table-bordered">

            <thead>

                <tr>

                    <th style="width:50px;">No</th>

                    <th>Uraian</th>

                    <th style="width:130px;">Proposal</th>

                    <th style="width:130px;">Realisasi</th>

                    <th style="width:130px;">Selisih</th>

                </tr>

            </thead>

            <tbody>

                @php

                    $totalProposal = 0;

                    $totalRealisasi = 0;

                @endphp

                @forelse($record->realisasiAnggaran as $item)
                    @php

                        $proposal = $item->proposalRab?->subtotal ?? 0;

                        $realisasi = $item->subtotal;

                        $selisih = $proposal - $realisasi;

                        $totalProposal += $proposal;

                        $totalRealisasi += $realisasi;

                    @endphp

                    <tr>

                        <td class="text-center">

                            {{ $loop->iteration }}

                        </td>

                        <td>

                            {{ $item->uraian }}

                        </td>

                        <td class="text-right">

                            Rp {{ number_format($proposal, 0, ',', '.') }}

                        </td>

                        <td class="text-right">

                            Rp {{ number_format($realisasi, 0, ',', '.') }}

                        </td>

                        <td class="text-right">

                            Rp {{ number_format($selisih, 0, ',', '.') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center">

                            Belum ada data realisasi anggaran.

                        </td>

                    </tr>
                @endforelse

            </tbody>

            <tfoot>

                <tr>

                    <td colspan="2" class="text-right font-bold">

                        TOTAL

                    </td>

                    <td class="text-right font-bold">

                        Rp {{ number_format($totalProposal, 0, ',', '.') }}

                    </td>

                    <td class="text-right font-bold">

                        Rp {{ number_format($totalRealisasi, 0, ',', '.') }}

                    </td>

                    <td class="text-right font-bold">

                        Rp {{ number_format($totalProposal - $totalRealisasi, 0, ',', '.') }}

                    </td>

                </tr>

            </tfoot>

        </table>

        <div class="mt-30 text-justify">

            Berdasarkan realisasi anggaran di atas, total penggunaan dana kegiatan adalah sebesar

            <strong>

                Rp {{ number_format($totalRealisasi, 0, ',', '.') }}

            </strong>

            dari total anggaran yang direncanakan sebesar

            <strong>

                Rp {{ number_format($totalProposal, 0, ',', '.') }}

            </strong>.

            Dengan demikian selisih anggaran adalah sebesar

            <strong>

                Rp {{ number_format($totalProposal - $totalRealisasi, 0, ',', '.') }}

            </strong>.

        </div>

    </div>

    <div class="page-break"></div>
    {{-- ========================= BAB V ========================= --}}

    <div>

        <h2>BAB V</h2>

        <h3>PENUTUP</h3>

        <div class="text-justify">

            {!! $record->penutup !!}

        </div>

    </div>

    <div class="page-break"></div>
    {{-- ========================= LAMPIRAN ========================= --}}

    <div>

        <h2>LAMPIRAN</h2>

        <h3>DOKUMENTASI KEGIATAN</h3>

        @forelse($record->dokumentasi ?? [] as $foto)
            <div style="margin-bottom:20px;text-align:center;">

                @if (file_exists(storage_path('app/public/' . $foto)))
                    <img src="{{ storage_path('app/public/' . $foto) }}"
                        style="
            width:90%;
            height:auto;
            border:1px solid #ccc;
            padding:5px;
        ">
                @endif

            </div>

        @empty

            <div class="text-center">

                Tidak ada dokumentasi.

            </div>
        @endforelse

    </div>

    <div class="page-break"></div>

    <div>

        <h2>LAMPIRAN</h2>

        <h3>BUKTI NOTA / TRANSAKSI</h3>

        @forelse($record->realisasiAnggaran as $item)
            <div style="margin-bottom:30px;">

                <h4>
                    {{ $loop->iteration }}.
                    {{ $item->uraian }}
                </h4>

                <table style="width:100%;margin-bottom:10px;">

                    <tr>

                        <td width="180">
                            Jumlah Realisasi
                        </td>

                        <td>
                            :
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>

                    </tr>

                </table>

                @if ($item->bukti && file_exists(storage_path('app/public/' . $item->bukti)))
                    <img src="{{ storage_path('app/public/' . $item->bukti) }}"
                        style="
                        width:90%;
                        height:auto;
                        border:1px solid #ccc;
                        padding:5px;
                    ">
                @else
                    <div
                        style="
                        border:1px dashed #999;
                        padding:15px;
                        text-align:center;
                    ">

                        Bukti transaksi tidak tersedia.

                    </div>
                @endif

            </div>

        @empty

            <div class="text-center">

                Tidak ada bukti transaksi.

            </div>
        @endforelse

    </div>

    <div class="page-break"></div>
    @if ($record->sertifikat && file_exists(storage_path('app/public/' . $record->sertifikat)))
        <div>

            <h2>LAMPIRAN</h2>

            <h3>SERTIFIKAT</h3>

            <img src="{{ storage_path('app/public/' . $record->sertifikat) }}" style="width:100%;">

        </div>

        <div class="page-break"></div>
    @endif
    @if ($record->berita_acara && file_exists(storage_path('app/public/' . $record->berita_acara)))
        <div>

            <h2>LAMPIRAN</h2>

            <h3>BERITA ACARA</h3>

            <img src="{{ storage_path('app/public/' . $record->berita_acara) }}" style="width:100%;">

        </div>

        <div class="page-break"></div>
    @endif
    <div>

        <div class="mt-40 text-center">
            <div class="font-bold uppercase" style="font-size: 20px;">LEMBAR PENGESAHAN</div>
            <div class="font-bold uppercase mt-10" style="font-size: 16px;">PROPOSAL KEGIATAN
                {{ $record->nama_kegiatan }}</div>
            <div class="mt-10">
                MTsN 3 Tulungagung <br> Tahun Pelajaran 2026/2027
            </div>
        </div>
        @if ($record->proposal->isPelatihMerangkapPembina())
            <table class="table-ttd">

                <tr>

                    <td width="50%">
                        Disusun dan Diperiksa oleh,<br>
                        Pelatih / Pembina Ekstrakurikuler
                    </td>

                    <td width="50%">
                        Disetujui oleh,<br>
                        Wakil Kepala Madrasah<br>
                        Bidang Kesiswaan
                    </td>

                </tr>

                <tr>

                    <td class="ttd-space"></td>

                    <td class="ttd-space"></td>

                </tr>

                <tr>

                    <td class="font-bold">

                        <u>
                            {{ $record->proposal->pelatih()?->pegawai?->nama ?? '................................' }}
                        </u>

                    </td>

                    <td class="font-bold">

                        <u>
                            {{ $record->proposal->wakaKesiswaan()?->name ?? '................................' }}
                        </u>

                    </td>

                </tr>

            </table>

            <table class="table-ttd">

                <tr>

                    <td>

                        Mengetahui,<br>
                        Kepala MTsN 3 Tulungagung

                    </td>

                </tr>

                <tr>

                    <td class="ttd-space"></td>

                </tr>

                <tr>

                    <td class="font-bold">

                        <u>
                            {{ $record->proposal->kepalaMadrasah()?->name ?? '................................' }}
                        </u>

                    </td>

                </tr>

            </table>
        @else
            <table class="table-ttd">
                <tr>
                    <td width="50%">Disusun oleh,<br>Pelatih</td>
                    <td width="50%">Diperiksa oleh,<br>Pembina Ekstrakurikuler</td>
                </tr>
                <tr>
                    <td class="ttd-space"></td>
                    <td class="ttd-space"></td>
                </tr>
                <tr>
                    <td class="font-bold">
                        <u>{{ $record->proposal->pelatih()?->pegawai?->nama ?? '................................' }}</u>
                    </td>
                    <td class="font-bold">
                        <u>{{ $record->proposal->pembina()?->pegawai?->nama ?? '................................' }}</u>
                    </td>
                </tr>
            </table>

            <table class="table-ttd">
                <tr>
                    <td width="50%">Disetujui oleh,<br>Wakil Kepala Madrasah<br>Bidang Kesiswaan</td>
                    <td width="50%">Mengetahui,<br>Kepala MTsN 3 Tulungagung</td>
                </tr>
                <tr>
                    <td class="ttd-space"></td>
                    <td class="ttd-space"></td>
                </tr>
                <tr>
                    <td class="font-bold">
                        <u>{{ $record->proposal->wakaKesiswaan()?->name ?? '................................' }}</u>
                    </td>
                    <td class="font-bold">
                        <u>{{ $record->proposal->kepalaMadrasah()?->name ?? '................................' }}</u>
                    </td>
                </tr>
            </table>
        @endif

    </div>

</body>

</html>
