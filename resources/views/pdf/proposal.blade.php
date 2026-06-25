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
            <img src="{{ public_path('images/logo-mtsn3.png') }}" alt="Logo Madrasah" class="logo">
            <div class="kop-instansi">KEMENTERIAN AGAMA REPUBLIK INDONESIA</div>
            <div class="kop-instansi">KANTOR KEMENTERIAN AGAMA KABUPATEN TULUNGAGUNG</div>
            <div class="kop-nama">MTsN 3 TULUNGAGUNG</div>
            <div class="kop-tahun">Tahun Pelajaran 2026/2027</div>
        </div>

        <hr class="garis-kop">

        <div class="text-center">
            <div class="judul-proposal">PROPOSAL KEGIATAN</div>
            <div class="nama-kegiatan">{{ $record->nama_kegiatan }}</div>
        </div>

        <div class="mt-40">
            <table class="table-info">
                <tr>
                    <td>Ekstrakurikuler</td>
                    <td>: {{ $record->ekstrakurikuler->nama }}</td>
                </tr>
                <tr>
                    <td>Jenis Kegiatan</td>
                    <td>: {{ $record->jenis_kegiatan_label }}</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>: {{ $record->tempat }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $record->tanggal_mulai?->translatedFormat('d F Y') }} s.d.
                        {{ $record->tanggal_selesai?->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Status Proposal</td>
                    <td>: {{ $record->status_label }}</td>
                </tr>
            </table>
        </div>

        <div class="cover-footer">MTsN 3 Tulungagung</div>
    </div>

    <div class="page-break"></div>

    {{-- ========================= BAB I ========================= --}}
    <div>
        <h2>BAB I</h2>
        <h3>PENDAHULUAN</h3>

        <h4>A. Latar Belakang</h4>
        <div class="text-justify mt-10">
            {!! $record->latar_belakang !!}
        </div>

        <h4>B. Dasar Kegiatan</h4>
        <div class="text-justify mt-10">
            {!! $record->dasar_kegiatan !!}
        </div>
        <div class="page-break"></div>
        <h2 class="mt-40">BAB II</h2>
        <h3>TUJUAN</h3>
        <div class="text-justify">
            {!! $record->tujuan !!}
        </div>
    </div>

    <div class="page-break"></div>

    {{-- ========================= BAB III ========================= --}}
    <div>
        <h2>BAB III</h2>
        <h3>PESERTA KEGIATAN</h3>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Peserta</th>
                    <th style="width: 80px;">JK</th>
                    <th style="width: 120px;">Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($record->peserta as $peserta)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $peserta->siswa->nama }}</td>
                        <td class="text-center">{{ $peserta->siswa->jenis_kelamin }}</td>
                        <td class="text-center">{{ $peserta->siswa->kelasAktif?->kelas?->nama ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 15px;">Belum ada peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    {{-- ========================= BAB IV ========================= --}}
    <div>
        <h2>BAB IV</h2>
        <h3>RENCANA ANGGARAN BIAYA</h3>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Uraian</th>
                    <th style="width: 70px;">Qty</th>
                    <th style="width: 90px;">Satuan</th>
                    <th style="width: 130px;">Harga</th>
                    <th style="width: 140px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($record->rabs as $rab)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $rab->uraian }}</td>
                        <td class="text-center">{{ $rab->qty }}</td>
                        <td class="text-center">{{ $rab->satuan }}</td>
                        <td class="text-right">Rp {{ number_format($rab->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($rab->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 15px;">Belum ada anggaran.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right font-bold" style="padding: 10px;">TOTAL ANGGARAN</td>
                    <td class="text-right font-bold" style="padding: 10px;">Rp
                        {{ number_format($record->total_anggaran, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <p class="text-justify indent mt-30">
            Berdasarkan rincian Rencana Anggaran Biaya (RAB) di atas, total dana yang diperlukan untuk melaksanakan
            kegiatan <strong>{{ $record->nama_kegiatan }}</strong> adalah sebesar:
        </p>

        <div class="text-center font-bold mb-20 mt-20" style="font-size: 20px;">
            Rp {{ number_format($record->total_anggaran, 0, ',', '.') }}
        </div>

        <p class="text-justify indent">
            Anggaran tersebut akan digunakan sebagai acuan dalam pelaksanaan kegiatan sesuai kebutuhan dan ketentuan
            yang berlaku.
        </p>
    </div>

    <div class="page-break"></div>

    {{-- ========================= BAB V & PENGESAHAN ========================= --}}
    <div>
        <h2>BAB V</h2>
        <h3>PENUTUP</h3>

        <div class="text-justify mb-30">
            {!! $record->penutup !!}
        </div>

        {{-- LEMBAR PENGESAHAN --}}
        <div class="mt-40 text-center">
            <div class="font-bold uppercase" style="font-size: 20px;">LEMBAR PENGESAHAN</div>
            <div class="font-bold uppercase mt-10" style="font-size: 16px;">PROPOSAL KEGIATAN
                {{ $record->nama_kegiatan }}</div>
            <div class="mt-10">
                MTsN 3 Tulungagung <br> Tahun Pelajaran 2026/2027
            </div>
        </div>

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
                <td class="font-bold"><u>{{ $record->createdBy?->name ?? '................................' }}</u></td>
                <td class="font-bold"><u>................................</u></td>
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
                <td class="font-bold"><u>................................</u></td>
                <td class="font-bold"><u>................................</u></td>
            </tr>
        </table>
    </div>

</body>

</html>
