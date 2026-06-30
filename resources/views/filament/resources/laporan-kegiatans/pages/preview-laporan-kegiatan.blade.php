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
                    LAPORAN KEGIATAN
                </div>

                <div
                    style="
                    font-size:22px;
                    margin-top:12px;
                    font-weight:bold;
                    text-transform:uppercase;
                ">
                    {{ $record->proposal->nama_kegiatan }}
                </div>

            </div>

            <div style="margin-top:60px;">

                <table
                    style="
                    width:100%;
                    border-collapse:collapse;
                    font-size:15px;
                ">

                    <tr>

                        <td style="width:220px;">
                            <strong>Ekstrakurikuler</strong>
                        </td>

                        <td>
                            :
                            {{ $record->proposal->ekstrakurikuler->nama }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Jenis Kegiatan</strong>
                        </td>

                        <td>
                            :
                            {{ $record->proposal->jenis_kegiatan_label }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Tempat</strong>
                        </td>

                        <td>
                            :
                            {{ $record->proposal->tempat }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Tanggal Pelaksanaan</strong>
                        </td>

                        <td>
                            :
                            {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }}
                            s.d.
                            {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Status Laporan</strong>
                        </td>

                        <td>
                            :
                            {{ $record->status_label }}
                        </td>

                    </tr>

                </table>

            </div>

            <div
                style="
                margin-top:90px;
                text-align:center;
                font-size:18px;
                font-weight:bold;
                text-transform:uppercase;
            ">

                MTsN 3 Tulungagung

            </div>

        </div>

    </div>

    <div style="page-break-after: always;"></div>
    <div
        style="
        width:210mm;
        min-height:297mm;
        margin:30px auto 0;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
        font-family:Arial, Helvetica, sans-serif;
        color:#000;
        line-height:1.7;
    ">

        <h2
            style="
            text-align:center;
            font-size:20px;
            font-weight:bold;
            margin-bottom:20px;
        ">
            BAB I
        </h2>

        <h3
            style="
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-bottom:35px;
        ">
            PENDAHULUAN
        </h3>

        {{-- ================================= --}}
        {{-- LATAR BELAKANG --}}
        {{-- ================================= --}}

        <h4 style="
            font-size:16px;
            font-weight:bold;
        ">
            A. Latar Belakang
        </h4>

        <div style="
            margin-top:10px;
            text-align:justify;
        ">

            {!! $record->proposal->latar_belakang !!}

        </div>

        {{-- ================================= --}}
        {{-- TUJUAN --}}
        {{-- ================================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            B. Tujuan
        </h4>

        <div style="
            margin-top:10px;
            text-align:justify;
        ">

            {!! $record->proposal->tujuan !!}

        </div>

        {{-- ================================= --}}
        {{-- IDENTITAS --}}
        {{-- ================================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            C. Identitas Kegiatan
        </h4>

        <table
            style="
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
            font-size:15px;
        ">

            <tr>

                <td style="width:220px;">
                    Nama Kegiatan
                </td>

                <td>
                    :
                    {{ $record->proposal->nama_kegiatan }}
                </td>

            </tr>

            <tr>

                <td>
                    Ekstrakurikuler
                </td>

                <td>
                    :
                    {{ $record->proposal->ekstrakurikuler->nama }}
                </td>

            </tr>

            <tr>

                <td>
                    Jenis Kegiatan
                </td>

                <td>
                    :
                    {{ $record->proposal->jenis_kegiatan_label }}
                </td>

            </tr>

            <tr>

                <td>
                    Tempat
                </td>

                <td>
                    :
                    {{ $record->proposal->tempat }}
                </td>

            </tr>

            <tr>

                <td>
                    Tanggal Pelaksanaan
                </td>

                <td>
                    :
                    {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }}
                    s.d.
                    {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}
                </td>

            </tr>

            <tr>

                <td>
                    Jumlah Peserta
                </td>

                <td>
                    :
                    {{ $record->peserta->count() }} Orang
                </td>

            </tr>

            <tr>

                <td>
                    Jumlah Pendamping
                </td>

                <td>
                    :
                    {{ $record->pendamping->count() }} Orang
                </td>

            </tr>

        </table>

    </div>

    <div style="page-break-after: always;"></div>
    <div
        style="
        width:210mm;
        min-height:297mm;
        margin:30px auto 0;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
        font-family:Arial, Helvetica, sans-serif;
        color:#000;
        line-height:1.7;
    ">

        <h2
            style="
        text-align:center;
        font-size:20px;
        font-weight:bold;
        margin-bottom:20px;
    ">
            BAB II
        </h2>

        <h3
            style="
        text-align:center;
        font-size:18px;
        font-weight:bold;
        margin-bottom:35px;
    ">
            PELAKSANAAN KEGIATAN
        </h3>

        {{-- ========================= --}}
        {{-- WAKTU DAN TEMPAT --}}
        {{-- ========================= --}}

        <h4 style="font-size:16px;font-weight:bold;">
            A. Waktu dan Tempat
        </h4>

        <table
            style="
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
            font-size:15px;
        ">

            <tr>

                <td style="width:220px;">
                    Hari / Tanggal
                </td>

                <td>
                    :
                    {{ $record->proposal->tanggal_mulai?->translatedFormat('d F Y') }}
                    s.d.
                    {{ $record->proposal->tanggal_selesai?->translatedFormat('d F Y') }}
                </td>

            </tr>

            <tr>

                <td>
                    Tempat
                </td>

                <td>
                    :
                    {{ $record->proposal->tempat }}
                </td>

            </tr>

            <tr>

                <td>
                    Jenis Kegiatan
                </td>

                <td>
                    :
                    {{ $record->proposal->jenis_kegiatan_label }}
                </td>

            </tr>

        </table>

        {{-- ========================= --}}
        {{-- PENDAMPING --}}
        {{-- ========================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            B. Pendamping Kegiatan
        </h4>

        <table
            style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
            margin-top:15px;
        ">

            <thead>

                <tr style="background:#f3f4f6;">

                    <th style="border:1px solid #000;padding:8px;width:50px;">
                        No
                    </th>

                    <th style="border:1px solid #000;padding:8px;">
                        Nama
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:170px;">
                        Peran
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:90px;">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach ($record->pendamping as $item)
                    <tr>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;">
                            {{ $item->pegawai->nama }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ ucfirst(str_replace('_', ' ', $item->peran)) }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ ucfirst($item->status) }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        {{-- ========================= --}}
        {{-- PESERTA --}}
        {{-- ========================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            C. Peserta Kegiatan
        </h4>

        <table
            style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
            margin-top:15px;
        ">

            <thead>

                <tr style="background:#f3f4f6;">

                    <th style="border:1px solid #000;padding:8px;width:50px;">
                        No
                    </th>

                    <th style="border:1px solid #000;padding:8px;">
                        Nama
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:80px;">
                        JK
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:120px;">
                        Kelas
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:90px;">
                        Status
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach ($record->peserta as $item)
                    <tr>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;">
                            {{ $item->siswa->nama }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $item->siswa->jenis_kelamin }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $item->siswa->kelasAktif?->kelas?->nama }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ ucfirst($item->status) }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>

        {{-- ========================= --}}
        {{-- REKAP --}}
        {{-- ========================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            D. Rekap Kehadiran Peserta
        </h4>

        <table
            style="
            width:50%;
            border-collapse:collapse;
            margin-top:15px;
            font-size:14px;
        ">

            <tr>

                <td style="border:1px solid #000;padding:8px;">
                    Hadir
                </td>

                <td style="border:1px solid #000;padding:8px;text-align:center;">
                    {{ $record->peserta->where('status', 'hadir')->count() }}
                </td>

            </tr>

            <tr>

                <td style="border:1px solid #000;padding:8px;">
                    Izin
                </td>

                <td style="border:1px solid #000;padding:8px;text-align:center;">
                    {{ $record->peserta->where('status', 'izin')->count() }}
                </td>

            </tr>

            <tr>

                <td style="border:1px solid #000;padding:8px;">
                    Sakit
                </td>

                <td style="border:1px solid #000;padding:8px;text-align:center;">
                    {{ $record->peserta->where('status', 'sakit')->count() }}
                </td>

            </tr>

            <tr>

                <td style="border:1px solid #000;padding:8px;">
                    Alpha
                </td>

                <td style="border:1px solid #000;padding:8px;text-align:center;">
                    {{ $record->peserta->where('status', 'alpha')->count() }}
                </td>

            </tr>

        </table>

        {{-- ========================= --}}
        {{-- URAIAN --}}
        {{-- ========================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            E. Uraian Pelaksanaan
        </h4>

        <div style="
            margin-top:15px;
            text-align:justify;
        ">

            {!! $record->uraian_pelaksanaan !!}

        </div>

    </div>

    <div style="page-break-after:always;"></div>
    <div
        style="
        width:210mm;
        min-height:297mm;
        margin:30px auto 0;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
        font-family:Arial, Helvetica, sans-serif;
        color:#000;
        line-height:1.7;
    ">

        <h2
            style="
            text-align:center;
            font-size:20px;
            font-weight:bold;
            margin-bottom:20px;
        ">
            BAB III
        </h2>

        <h3
            style="
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-bottom:35px;
        ">
            HASIL KEGIATAN
        </h3>

        {{-- ========================= --}}
        {{-- PENCAPAIAN --}}
        {{-- ========================= --}}

        <h4 style="font-size:16px;font-weight:bold;">
            A. Pencapaian
        </h4>

        <div style="
            margin-top:15px;
            text-align:justify;
        ">

            {!! $record->pencapaian !!}

        </div>

        {{-- ========================= --}}
        {{-- PRESTASI --}}
        {{-- ========================= --}}

        @if (!empty(strip_tags($record->prestasi ?? '')))
            <h4
                style="
                margin-top:35px;
                font-size:16px;
                font-weight:bold;
            ">
                B. Prestasi
            </h4>

            <div style="
                margin-top:15px;
                text-align:justify;
            ">

                {!! $record->prestasi !!}

            </div>
        @endif

        {{-- ========================= --}}
        {{-- EVALUASI --}}
        {{-- ========================= --}}

        <h4 style="
            margin-top:35px;
            font-size:16px;
            font-weight:bold;
        ">
            {{ !empty(strip_tags($record->prestasi ?? '')) ? 'C' : 'B' }}. Evaluasi
        </h4>

        <div style="
            margin-top:15px;
            text-align:justify;
        ">

            {!! $record->evaluasi !!}

        </div>

    </div>

    <div style="page-break-after:always;"></div>
    <div
        style="
        width:210mm;
        min-height:297mm;
        margin:30px auto 0;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
        font-family:Arial, Helvetica, sans-serif;
    ">

        <h2 style="
            text-align:center;
            font-size:20px;
            font-weight:bold;
        ">
            BAB IV
        </h2>

        <h3
            style="
            text-align:center;
            font-size:18px;
            font-weight:bold;
            margin-bottom:30px;
        ">
            REALISASI ANGGARAN
        </h3>

        <table
            style="
            width:100%;
            border-collapse:collapse;
            font-size:13px;
        ">

            <thead>

                <tr style="background:#f3f4f6;">

                    <th style="border:1px solid #000;padding:8px;width:40px;">
                        No
                    </th>

                    <th style="border:1px solid #000;padding:8px;">
                        Uraian
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:130px;">
                        Proposal
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:130px;">
                        Realisasi
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:130px;">
                        Selisih
                    </th>

                </tr>

            </thead>

            <tbody>

                @php

                    $totalProposal = 0;
                    $totalRealisasi = 0;

                @endphp

                @foreach ($record->realisasiAnggaran as $item)
                    @php

                        $proposal = $item->proposalRab?->subtotal ?? 0;

                        $realisasi = $item->subtotal;

                        $selisih = $proposal - $realisasi;

                        $totalProposal += $proposal;

                        $totalRealisasi += $realisasi;

                    @endphp

                    <tr>

                        <td
                            style="
                        border:1px solid #000;
                        padding:8px;
                        text-align:center;
                    ">

                            {{ $loop->iteration }}

                        </td>

                        <td style="border:1px solid #000;padding:8px;">

                            {{ $item->uraian }}

                        </td>

                        <td
                            style="
                        border:1px solid #000;
                        padding:8px;
                        text-align:right;
                    ">

                            Rp {{ number_format($proposal, 0, ',', '.') }}

                        </td>

                        <td
                            style="
                        border:1px solid #000;
                        padding:8px;
                        text-align:right;
                    ">

                            Rp {{ number_format($realisasi, 0, ',', '.') }}

                        </td>

                        <td
                            style="
                        border:1px solid #000;
                        padding:8px;
                        text-align:right;
                    ">

                            Rp {{ number_format($selisih, 0, ',', '.') }}

                        </td>

                    </tr>
                @endforeach

            </tbody>

            <tfoot>

                <tr style="
                background:#f3f4f6;
                font-weight:bold;
            ">

                    <td colspan="2"
                        style="
                    border:1px solid #000;
                    padding:8px;
                    text-align:center;
                ">

                        TOTAL

                    </td>

                    <td
                        style="
                    border:1px solid #000;
                    padding:8px;
                    text-align:right;
                ">

                        Rp {{ number_format($totalProposal, 0, ',', '.') }}

                    </td>

                    <td
                        style="
                    border:1px solid #000;
                    padding:8px;
                    text-align:right;
                ">

                        Rp {{ number_format($totalRealisasi, 0, ',', '.') }}

                    </td>

                    <td
                        style="
                    border:1px solid #000;
                    padding:8px;
                    text-align:right;
                ">

                        Rp {{ number_format($totalProposal - $totalRealisasi, 0, ',', '.') }}

                    </td>

                </tr>

            </tfoot>

        </table>

    </div>

    <div style="page-break-after:always;"></div>
    <div
        style="
        width:210mm;
        min-height:297mm;
        margin:30px auto;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
        ">

        <h2 style="text-align:center;font-size:20px;font-weight:bold;">
            LAMPIRAN
        </h2>

        <h3 style="text-align:center;font-size:18px;font-weight:bold;margin-bottom:30px;">
            DOKUMENTASI KEGIATAN
        </h3>

        @if (!empty($record->dokumentasi))

            <div
                style="
                display:flex;
                flex-wrap:wrap;
                gap:15px;
            ">

                @foreach ($record->dokumentasi as $foto)
                    <div
                        style="
                        width:45%;
                        border:1px solid #ccc;
                        padding:8px;
                    ">

                        <img src="{{ Storage::url($foto) }}"
                            style="
                            width:100%;
                            height:auto;
                        ">

                    </div>
                @endforeach

            </div>
        @else
            <p>Tidak ada dokumentasi.</p>

        @endif

    </div>

    <div style="page-break-after:always;"></div>

    <div
        style="
    width:210mm;
    min-height:297mm;
    margin:30px auto;
    background:white;
    padding:25mm;
    box-shadow:0 0 15px rgba(0,0,0,.15);
">

        <h2 style="text-align:center;">
            LAMPIRAN
        </h2>

        <h3 style="text-align:center;margin-bottom:30px;">
            BUKTI TRANSAKSI
        </h3>

        @forelse($record->realisasiAnggaran as $item)
            <h4 style="margin-bottom:10px;">
                {{ $loop->iteration }}.
                {{ $item->uraian }}
            </h4>

            @if ($item->bukti)
                <img src="{{ Storage::url($item->bukti) }}"
                    style="
                    width:100%;
                    margin-bottom:25px;
                    border:1px solid #ccc;
                ">
            @else
                <p style="margin-bottom:25px;">
                    Tidak ada bukti transaksi.
                </p>
            @endif

        @empty

            <p>Tidak ada realisasi anggaran.</p>
        @endforelse

    </div>

    <div style="page-break-after:always;"></div>
    @if ($record->sertifikat)
        <div
            style="
        width:210mm;
        min-height:297mm;
        margin:30px auto;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
    ">

            <h2 style="text-align:center;">
                LAMPIRAN
            </h2>

            <h3 style="text-align:center;margin-bottom:30px;">
                SERTIFIKAT
            </h3>

            <img src="{{ Storage::url($record->sertifikat) }}" style="
            width:100%;
        ">

        </div>

        <div style="page-break-after:always;"></div>
    @endif
    @if ($record->berita_acara)
        <div
            style="
        width:210mm;
        min-height:297mm;
        margin:30px auto;
        background:white;
        padding:25mm;
        box-shadow:0 0 15px rgba(0,0,0,.15);
    ">

            <h2 style="text-align:center;">
                LAMPIRAN
            </h2>

            <h3 style="text-align:center;margin-bottom:30px;">
                BERITA ACARA
            </h3>

            <img src="{{ Storage::url($record->berita_acara) }}" style="width:100%;">

        </div>

        <div style="page-break-after:always;"></div>
    @endif
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
                LEMBAR PENGESAHAN
            </div>

            <div
                style="
                margin-top:10px;
                font-size:17px;
                font-weight:bold;
                text-transform:uppercase;
            ">
                PROPOSAL KEGIATAN
            </div>

            <div
                style="
                margin-top:6px;
                font-size:18px;
                font-weight:bold;
            ">
                {{ strtoupper($record->nama_kegiatan) }}
            </div>

            <div style="
                margin-top:6px;
                font-size:15px;
            ">
                MTsN 3 Tulungagung
                <br>
                Tahun Pelajaran 2026/2027
            </div>

        </div>
        @if ($record->proposal->isPelatihMerangkapPembina())
            <table
                style="
            width:100%;
            border-collapse:collapse;
            text-align:center;
            font-size:15px;
        ">

                <tr>

                    <td width="50%">
                        Disusun dan Diperiksa oleh,
                        <br>
                        Pelatih / Pembina Ekstrakurikuler
                    </td>

                    <td width="50%">
                        Disetujui oleh,
                        <br>
                        Wakil Kepala Madrasah
                        <br>
                        Bidang Kesiswaan
                    </td>

                </tr>

                <tr>

                    <td style="height:90px;"></td>

                    <td></td>

                </tr>

                <tr>

                    <td>
                        <strong>
                            {{ $record->proposal->pelatih()?->pegawai?->nama ?? '................................' }}
                        </strong>
                    </td>

                    <td>
                        <strong>
                            {{ $record->proposal->wakaKesiswaan()?->name ?? '................................' }}
                        </strong>
                    </td>

                </tr>

            </table>

            <table
                style="
            width:100%;
            margin-top:70px;
            border-collapse:collapse;
            text-align:center;
            font-size:15px;
        ">

                <tr>

                    <td>

                        Mengetahui,
                        <br>
                        Kepala MTsN 3 Tulungagung

                    </td>

                </tr>

                <tr>

                    <td style="height:90px;"></td>

                </tr>

                <tr>

                    <td>

                        <strong>
                            {{ $record->proposal->kepalaMadrasah()?->name ?? '................................' }}
                        </strong>

                    </td>

                </tr>

            </table>
        @else
            <table
                style="
            width:100%;
            border-collapse:collapse;
            text-align:center;
            font-size:15px;
        ">

                <tr>

                    <td width="50%">
                        Disusun oleh,
                        <br>
                        Pelatih
                    </td>

                    <td width="50%">
                        Diperiksa oleh,
                        <br>
                        Pembina Ekstrakurikuler
                    </td>

                </tr>

                <tr>

                    <td style="height:90px;"></td>

                    <td></td>

                </tr>

                <tr>

                    <td>
                        <strong>
                            {{ $record->proposal->pelatih()?->pegawai?->nama ?? '................................' }}
                        </strong>
                    </td>

                    <td>
                        <strong>
                            {{ $record->proposal->pembina()?->pegawai?->nama ?? '................................' }}
                        </strong>
                    </td>

                </tr>

            </table>

            <table
                style="
            width:100%;
            margin-top:70px;
            border-collapse:collapse;
            text-align:center;
            font-size:15px;
        ">

                <tr>

                    <td width="50%">
                        Disetujui oleh,
                        <br>
                        Wakil Kepala Madrasah
                        <br>
                        Bidang Kesiswaan
                    </td>

                    <td width="50%">
                        Mengetahui,
                        <br>
                        Kepala MTsN 3 Tulungagung
                    </td>

                </tr>

                <tr>

                    <td style="height:90px;"></td>

                    <td></td>

                </tr>

                <tr>

                    <td>
                        <strong>
                            {{ $record->proposal->wakaKesiswaan()?->name ?? '................................' }}
                        </strong>
                    </td>

                    <td>
                        <strong>
                            {{ $record->proposal->kepalaMadrasah()?->name ?? '................................' }}
                        </strong>
                    </td>

                </tr>

            </table>
        @endif

    </div>

</x-filament-panels::page>
