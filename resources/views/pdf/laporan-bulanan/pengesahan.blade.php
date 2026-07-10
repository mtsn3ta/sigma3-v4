<div>

    <div style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #111827; padding-bottom: 2rem;">
        <h1 style="font-size: 1.375rem; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">
            LEMBAR PENGESAHAN
        </h1>
        <h2
            style="font-size: 1.125rem; font-weight: 700; text-transform: uppercase; margin: 0.75rem 0 0 0; letter-spacing: 0.05em; color: #374151;">
            LAPORAN BULANAN
        </h2>
        <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0.75rem 0 0 0; color: #111827;">
            {{ strtoupper($record->ekstrakurikuler->nama) }}
        </h3>
        <p style="font-size: 1rem; font-weight: 500; margin: 0.25rem 0 0 0; color: #374151;">
            Bulan {{ \Carbon\Carbon::create()->month($record->bulan)->translatedFormat('F') }} {{ $record->tahun }}
        </p>
        <p style="font-size: 0.9375rem; margin: 0.75rem 0 0 0; color: #4b5563;">
            MTsN 3 Tulungagung <br>
            Tahun Pelajaran 2026/2027
        </p>
    </div>

    {{-- Tanda Tangan: Mode Pelatih Merangkap Pembina (3 Orang) --}}
    @if ($record->isPelatihMerangkapPembina())
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

                <td class="ttd-space">{!! $renderTtdPdf($pelatihTtd, $pembinaApproved, -4) !!}</td>

                <td class="ttd-space">{!! $renderTtdPdf($wakaTtd, $wakaApproved, 4) !!}</td>

            </tr>

            <tr>

                <td class="font-bold">

                    <u>
                        {{ $record->pelatih()?->pegawai?->nama ?? '................................' }}
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
    @else
        <table class="table-ttd">
            <tr>
                <td width="50%">Disusun oleh,<br>Pelatih</td>
                <td width="50%">Diperiksa oleh,<br>Pembina Ekstrakurikuler</td>
            </tr>
            <tr>
                <td class="ttd-space"></td>
                <td class="ttd-space">{!! $renderTtdPdf($pembinaTtd, $pembinaApproved, 4) !!}</td>
            </tr>
            <tr>
                <td class="font-bold">
                    <u>{{ $record->pelatih()?->pegawai?->nama ?? '................................' }}</u>
                </td>
                <td class="font-bold">
                    <u>{{ $record->pembina()?->pegawai?->nama ?? '................................' }}</u>
                </td>
            </tr>
        </table>

        <table class="table-ttd">
            <tr>
                <td width="50%">Disetujui oleh,<br>Wakil Kepala Madrasah<br>Bidang Kesiswaan</td>
                <td width="50%">Mengetahui,<br>Kepala MTsN 3 Tulungagung</td>
            </tr>
            <tr>
                <td class="ttd-space">{!! $renderTtdPdf($wakaTtd, $wakaApproved, -4) !!}</td>
                <td class="ttd-space">{!! $renderTtdPdf($kepalaTtd, $kepalaApproved, 3) !!}</td>
            </tr>
            <tr>
                <td class="font-bold">
                    <u>{{ $record->wakaKesiswaan()?->pegawai?->nama ?? '................................' }}</u>
                </td>
                <td class="font-bold">
                    <u>{{ $record->kepalaMadrasah()?->pegawai?->nama ?? '................................' }}</u>
                </td>
            </tr>
        </table>
    @endif

</div>
