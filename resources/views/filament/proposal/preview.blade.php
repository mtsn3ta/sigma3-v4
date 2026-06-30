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
                        alt="Logo Madrasah"
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

                <div
                    style="
            font-size:15px;
            font-weight:bold;
            text-transform:uppercase;
        ">

                    KANTOR KEMENTERIAN AGAMA KABUPATEN TULUNGAGUNG

                </div>

                <div
                    style="
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
                    PROPOSAL KEGIATAN
                </div>

                <div
                    style="
                font-size:22px;
                margin-top:12px;
                font-weight:bold;
                text-transform:uppercase;
            ">
                    {{ $record->nama_kegiatan }}
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
                            {{ $record->ekstrakurikuler->nama }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Jenis Kegiatan</strong>
                        </td>

                        <td>
                            :
                            {{ $record->jenis_kegiatan_label }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Tempat</strong>
                        </td>

                        <td>
                            :
                            {{ $record->tempat }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Tanggal</strong>
                        </td>

                        <td>
                            :
                            {{ $record->tanggal_mulai?->translatedFormat('d F Y') }}
                            s.d.
                            {{ $record->tanggal_selesai?->translatedFormat('d F Y') }}
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <strong>Status Proposal</strong>
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
        <h2 style="
    text-align:center;
    font-size:20px;
    font-weight:bold;
    margin-bottom:25px;
">
            BAB I
        </h2>

        <h3 style="
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin-bottom:35px;
">
            PENDAHULUAN
        </h3>
        <h4 style="
    font-size:16px;
    font-weight:bold;
">
            A. Latar Belakang
        </h4>

        <div style="
    text-align:justify;
    margin-top:10px;
">
            {!! $record->latar_belakang !!}
        </div>
        <h4 style="
    margin-top:30px;
    font-size:16px;
    font-weight:bold;
">
            B. Dasar Kegiatan
        </h4>

        <div style="
    text-align:justify;
    margin-top:10px;
">
            {!! $record->dasar_kegiatan !!}
        </div>
        <h2 style="
    text-align:center;
    margin-top:60px;
    font-size:20px;
    font-weight:bold;
">
            BAB II
        </h2>

        <h3 style="
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin-bottom:30px;
">
            TUJUAN
        </h3>

        <div style="
    text-align:justify;
">
            {!! $record->tujuan !!}
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
">

        <h2 style="text-align:center;font-size:20px;font-weight:bold;">
            BAB III
        </h2>

        <h3 style="text-align:center;font-size:18px;font-weight:bold;margin-bottom:30px;">
            PESERTA KEGIATAN
        </h3>

        <table
            style="
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        margin-top:20px;
    ">

            <thead>

                <tr style="background:#f3f4f6;">

                    <th style="border:1px solid #000;padding:8px;width:50px;">
                        No
                    </th>

                    <th style="border:1px solid #000;padding:8px;">
                        Nama Peserta
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:80px;">
                        JK
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:120px;">
                        Kelas
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($record->peserta as $peserta)
                    <tr>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;">
                            {{ $peserta->siswa->nama }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $peserta->siswa->jenis_kelamin }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $peserta->siswa->kelasAktif?->kelas?->nama ?? '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" style="border:1px solid #000;padding:10px;text-align:center;">

                            Belum ada peserta.

                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>
        @if ($record->pendamping->count())

            <h4
                style="
            margin-top:35px;
            margin-bottom:15px;
            font-size:16px;
            font-weight:bold;
        ">
                Pendamping Kegiatan
            </h4>

            <table
                style="
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        ">

                <thead>

                    <tr style="background:#f3f4f6;">

                        <th style="border:1px solid #000;padding:8px;width:50px;">
                            No
                        </th>

                        <th style="border:1px solid #000;padding:8px;">
                            Nama Pendamping
                        </th>

                        <th style="border:1px solid #000;padding:8px;width:180px;">
                            Peran
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($record->pendamping as $pendamping)
                        <tr>

                            <td style="border:1px solid #000;padding:8px;text-align:center;">
                                {{ $loop->iteration }}
                            </td>

                            <td style="border:1px solid #000;padding:8px;">
                                {{ $pendamping->pegawai->nama }}
                            </td>

                            <td style="border:1px solid #000;padding:8px;text-align:center;">

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

    <div style="page-break-after: always;"></div>
    <div
        style="
    width:210mm;
    min-height:297mm;
    margin:30px auto 0;
    background:white;
    padding:25mm;
    box-shadow:0 0 15px rgba(0,0,0,.15);
">

        <h2 style="text-align:center;font-size:20px;font-weight:bold;">
            BAB IV
        </h2>

        <h3 style="text-align:center;font-size:18px;font-weight:bold;margin-bottom:30px;">
            RENCANA ANGGARAN BIAYA
        </h3>

        <table
            style="
        width:100%;
        border-collapse:collapse;
        font-size:14px;
        margin-top:20px;
    ">

            <thead>

                <tr style="background:#f3f4f6;">

                    <th style="border:1px solid #000;padding:8px;width:50px;">
                        No
                    </th>

                    <th style="border:1px solid #000;padding:8px;">
                        Uraian
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:70px;">
                        Qty
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:90px;">
                        Satuan
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:140px;">
                        Harga
                    </th>

                    <th style="border:1px solid #000;padding:8px;width:150px;">
                        Subtotal
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($record->rabs as $rab)
                    <tr>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;">
                            {{ $rab->uraian }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $rab->qty }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:center;">
                            {{ $rab->satuan }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:right;">
                            Rp {{ number_format($rab->harga_satuan, 0, ',', '.') }}
                        </td>

                        <td style="border:1px solid #000;padding:8px;text-align:right;">
                            Rp {{ number_format($rab->subtotal, 0, ',', '.') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" style="border:1px solid #000;padding:10px;text-align:center;">

                            Belum ada anggaran.

                        </td>

                    </tr>
                @endforelse

            </tbody>

            <tfoot>

                <tr>

                    <td colspan="5"
                        style="
                    border:1px solid #000;
                    padding:10px;
                    font-weight:bold;
                    text-align:right;
                ">

                        TOTAL

                    </td>

                    <td
                        style="
                    border:1px solid #000;
                    padding:10px;
                    font-weight:bold;
                    text-align:right;
                ">

                        Rp {{ number_format($record->total_anggaran, 0, ',', '.') }}

                    </td>

                </tr>

            </tfoot>

        </table>

        <div style="margin-top:25px;">

            <table
                style="
            width:45%;
            margin-left:auto;
            border-collapse:collapse;
            font-size:15px;
        ">

                <tr>

                    <td
                        style="
                    padding:8px;
                    font-weight:bold;
                    border:1px solid #000;
                ">
                        Total Anggaran
                    </td>

                    <td
                        style="
                    padding:8px;
                    text-align:right;
                    font-weight:bold;
                    border:1px solid #000;
                ">
                        Rp {{ number_format($record->total_anggaran, 0, ',', '.') }}
                    </td>

                </tr>

            </table>

        </div>

        <p style="
        margin-top:25px;
        text-align:justify;
        text-indent:40px;
    ">

            Berdasarkan rincian Rencana Anggaran Biaya (RAB) di atas,
            total dana yang diperlukan untuk melaksanakan kegiatan
            <strong>{{ $record->nama_kegiatan }}</strong>
            adalah sebesar:

        </p>

        <div
            style="
        margin:20px 0;
        text-align:center;
        font-size:20px;
        font-weight:bold;
    ">

            Rp {{ number_format($record->total_anggaran, 0, ',', '.') }}

        </div>

        <p style="
        text-align:justify;
        text-indent:40px;
    ">

            Anggaran tersebut akan digunakan sebagai acuan dalam
            pelaksanaan kegiatan sesuai kebutuhan dan ketentuan
            yang berlaku.

        </p>

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
">

        <h2 style="text-align:center;font-size:20px;font-weight:bold;">
            BAB V
        </h2>

        <h3 style="text-align:center;font-size:18px;font-weight:bold;margin-bottom:30px;">
            PENUTUP
        </h3>

        {!! $record->penutup !!}

        <div style="margin-top:70px;">

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
            @if ($record->isPelatihMerangkapPembina())
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
                                {{ $record->pelatih()?->pegawai?->nama ?? '................................' }}
                            </strong>
                        </td>

                        <td>
                            <strong>
                                {{ $record->wakaKesiswaan()?->name ?? '................................' }}
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
                                {{ $record->kepalaMadrasah()?->name ?? '................................' }}
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
                                {{ $record->pelatih()?->pegawai?->nama ?? '................................' }}
                            </strong>
                        </td>

                        <td>
                            <strong>
                                {{ $record->pembina()?->pegawai?->nama ?? '................................' }}
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
                                {{ $record->wakaKesiswaan()?->name ?? '................................' }}
                            </strong>
                        </td>

                        <td>
                            <strong>
                                {{ $record->kepalaMadrasah()?->name ?? '................................' }}
                            </strong>
                        </td>

                    </tr>

                </table>
            @endif

        </div>

    </div>

</x-filament-panels::page>
