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
        <table
            style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.9375rem; line-height: 1.5;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    Disusun dan Diperiksa oleh,<br>
                    Pelatih / Pembina Ekstrakurikuler
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    Disetujui oleh,<br>
                    Wakil Kepala Madrasah<br>
                    Bidang Kesiswaan
                </td>
            </tr>
            <tr>
                <td style="height: 100px;"></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding: 0 1rem;">
                    <strong style="text-decoration: underline;">
                        {{ $record->pelatih()?->pegawai?->nama ?? '........................................' }}
                    </strong>
                </td>
                <td style="padding: 0 1rem;">
                    <strong style="text-decoration: underline;">
                        {{ $record->wakaKesiswaan()?->name ?? '........................................' }}
                    </strong>
                </td>
            </tr>
        </table>

        <table
            style="width: 100%; margin-top: 3rem; border-collapse: collapse; text-align: center; font-size: 0.9375rem; line-height: 1.5;">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala MTsN 3 Tulungagung
                </td>
            </tr>
            <tr>
                <td style="height: 100px;"></td>
            </tr>
            <tr>
                <td>
                    <strong style="text-decoration: underline;">
                        {{ $record->kepalaMadrasah()?->name ?? '........................................' }}
                    </strong>
                </td>
            </tr>
        </table>

        {{-- Tanda Tangan: Mode Normal (4 Orang) --}}
    @else
        <table
            style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.9375rem; line-height: 1.5;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    Disusun oleh,<br>
                    Pelatih
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    Diperiksa oleh,<br>
                    Pembina Ekstrakurikuler
                </td>
            </tr>
            <tr>
                <td style="height: 100px;"></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding: 0 1rem;">
                    <strong style="text-decoration: underline;">
                        {{ $record->pelatih()?->pegawai?->nama ?? '........................................' }}
                    </strong>
                </td>
                <td style="padding: 0 1rem;">
                    <strong style="text-decoration: underline;">
                        {{ $record->pembina()?->pegawai?->nama ?? '........................................' }}
                    </strong>
                </td>
            </tr>
        </table>

        <table
            style="width: 100%; margin-top: 3rem; border-collapse: collapse; text-align: center; font-size: 0.9375rem; line-height: 1.5;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    Disetujui oleh,<br>
                    Wakil Kepala Madrasah<br>
                    Bidang Kesiswaan
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    Mengetahui,<br>
                    Kepala MTsN 3 Tulungagung
                </td>
            </tr>
            <tr>
                <td style="height: 100px;"></td>
                <td></td>
            </tr>
            <tr>
                <td style="padding: 0 1rem;">
                    <strong style="text-decoration: underline;">
                        {{ $record->wakaKesiswaan()?->name ?? '........................................' }}
                    </strong>
                </td>
                <td style="padding: 0 1rem;">
                    <strong style="text-decoration: underline;">
                        {{ $record->kepalaMadrasah()?->name ?? '........................................' }}
                    </strong>
                </td>
            </tr>
        </table>
    @endif

</div>
