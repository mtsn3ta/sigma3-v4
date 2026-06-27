<div>

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
            LAPORAN BULANAN
        </div>

        <div
            style="
                margin-top:6px;
                font-size:18px;
                font-weight:bold;
            ">
            {{ strtoupper($record->ekstrakurikuler->nama) }}

            Bulan
            {{ \Carbon\Carbon::create()->month($record->bulan)->translatedFormat('F') }}
            {{ $record->tahun }}
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
