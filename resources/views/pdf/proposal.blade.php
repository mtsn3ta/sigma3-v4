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
            height: 50px;
            text-align: center;
        }
    </style>
</head>

<body>

    @php
        $pembinaApproved = in_array($record->status, ['pending_waka', 'pending_kepala', 'approved']);
        $wakaApproved = in_array($record->status, ['pending_kepala', 'approved']);
        $kepalaApproved = $record->status === 'approved';

        $pelatihTtd = $record->pelatih()?->pegawai?->ttd_digital;
        $pembinaTtd = $record->pembina()?->pegawai?->ttd_digital;
        $wakaTtd = $record->wakaKesiswaan()?->pegawai?->ttd_digital;
        $kepalaTtd = $record->kepalaMadrasah()?->pegawai?->ttd_digital;

        $renderTtdPdf = function (?string $path, bool $isApproved, float $rotate = 0) {
            if (!$isApproved || blank($path)) {
                return '';
            }

            $fullPath = public_path('storage/' . $path);

            if (!file_exists($fullPath)) {
                return '';
            }

            return '<img src="' .
                $fullPath .
                '" style="display:block; margin:-32px auto -18px auto; max-width:190px; max-height:100px; transform:rotate(' .
                $rotate .
                'deg);">';
        };
    @endphp

    {{-- ========================= COVER ========================= --}}
    <div class="cover-container">

        <div class="text-center">

            @if (\App\Support\SettingHelper::schoolOfficialLogo())
                <img src="{{ public_path('storage/' . \App\Support\SettingHelper::schoolOfficialLogo()) }}"
                    alt="Logo Madrasah" class="logo">
            @endif

            <div class="kop-instansi">

                KEMENTERIAN AGAMA REPUBLIK INDONESIA

            </div>

            <div class="kop-instansi">

                KANTOR KEMENTERIAN AGAMA KABUPATEN TULUNGAGUNG

            </div>

            <div class="kop-nama">

                {{ \App\Support\SettingHelper::schoolName() }}

            </div>

            <div class="kop-tahun">

                Tahun Pelajaran
                {{ \App\Support\SettingHelper::tahunAjaranLabel() }}

            </div>

        </div>

        <hr class="garis-kop">

        <div class="text-center">

            <div class="judul-proposal">

                PROPOSAL KEGIATAN

            </div>

            <div class="nama-kegiatan">

                {{ $record->nama_kegiatan }}

            </div>

        </div>

        <div class="mt-40">

            <table class="table-info">

                <tr>

                    <td>Ekstrakurikuler</td>

                    <td>

                        : {{ $record->ekstrakurikuler->nama }}

                    </td>

                </tr>

                <tr>

                    <td>Jenis Kegiatan</td>

                    <td>

                        : {{ $record->jenis_kegiatan_label }}

                    </td>

                </tr>

                <tr>

                    <td>Tempat</td>

                    <td>

                        : {{ $record->tempat }}

                    </td>

                </tr>

                <tr>

                    <td>Tanggal</td>

                    <td>

                        :

                        {{ $record->tanggal_mulai?->translatedFormat('d F Y') }}

                        s.d.

                        {{ $record->tanggal_selesai?->translatedFormat('d F Y') }}

                    </td>

                </tr>

                <tr>

                    <td>Status Proposal</td>

                    <td>

                        : {{ $record->status_label }}

                    </td>

                </tr>

            </table>

        </div>

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
                        <td colspan="4" class="text-center" style="padding: 15px;">
                            Belum ada peserta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($record->pendamping->count())

            <h4 style="margin-top:25px; margin-bottom:10px;">
                Pendamping Kegiatan
            </h4>

            <table class="table-bordered">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Nama Pendamping</th>
                        <th style="width:180px;">Peran</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($record->pendamping as $pendamping)
                        <tr>

                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $pendamping->pegawai->nama }}
                            </td>

                            <td class="text-center">

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

        @endif

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

                    <td width="50%">
                        Disusun oleh,<br>
                        Pembina Ekstrakurikuler
                    </td>

                    <td width="50%">
                        Disetujui oleh,<br>
                        Wakil Kepala Madrasah<br>
                        Bidang Kesiswaan
                    </td>

                </tr>

                <tr>

                    <td class="ttd-space">{!! $renderTtdPdf($pembinaTtd, $pembinaApproved, -4) !!}</td>

                    <td class="ttd-space">{!! $renderTtdPdf($wakaTtd, $wakaApproved, 4) !!}</td>

                </tr>

                <tr>

                    <td class="font-bold">

                        <u>
                            {{ $record->pembina()?->pegawai?->nama ?? '................................' }}
                        </u>

                    </td>

                    <td class="font-bold">

                        <u>
                            {{ $record->wakaKesiswaan()?->pegawai?->nama ?? '................................' }}
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

                    <td class="ttd-space">{!! $renderTtdPdf($kepalaTtd, $kepalaApproved, -2) !!}</td>

                </tr>

                <tr>

                    <td class="font-bold">

                        <u>
                            {{ $record->kepalaMadrasah()?->pegawai?->nama ?? '................................' }}
                        </u>

                    </td>

                </tr>

            </table>

    </div>

</body>

</html>
