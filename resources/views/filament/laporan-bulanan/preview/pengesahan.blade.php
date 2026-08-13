@php
    use Illuminate\Support\Facades\Storage;

    $pembinaApproved = in_array($record->status, ['pending_waka', 'pending_kepala', 'approved']);
    $wakaApproved = in_array($record->status, ['pending_kepala', 'approved']);
    $kepalaApproved = $record->status === 'approved';

    $pelatihTtd = $record->pelatih()?->pegawai?->ttd_digital;
    $pembinaTtd = $record->pembina()?->pegawai?->ttd_digital;
    $wakaTtd = $record->wakaKesiswaan()?->pegawai?->ttd_digital;
    $kepalaTtd = $record->kepalaMadrasah()?->pegawai?->ttd_digital;

    $signatureImg = function (?string $path, bool $isApproved, float $rotate = 0) {
        if (!$isApproved || blank($path)) {
            return '';
        }

        $url = Storage::disk('public')->url($path);

        return '<img src="' .
            $url .
            '" style="position:absolute; top:60%; left:50%; transform:translate(-50%, -50%) rotate(' .
            $rotate .
            'deg); max-width:230px; max-height:130px; z-index:2; pointer-events:none;">';
    };
@endphp

<div
    style="
    width: 210mm;
    min-height: 297mm;
    margin: 2rem auto;
    background: #ffffff;
    padding: 25mm;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    box-sizing: border-box;
    color: #111827;
    font-family: Arial, sans-serif;
">

    {{-- Header Pengesahan --}}
    <div style="text-align: center; margin-bottom: 3rem; border-bottom: 2px solid #111827; padding-bottom: 2rem;">
        {{-- ... kode judul di atasnya ... --}}

        <p style="font-size: 1rem; font-weight: 500; margin: 0.25rem 0 0 0; color: #374151;">
            Bulan {{ \Carbon\Carbon::create()->month((int) $record->bulan)->translatedFormat('F') }}
            {{ $record->tahun }}
        </p>

        @php
            $b = (int) $record->bulan;
            $t = (int) $record->tahun;
            $tapel = $b >= 7 ? $t . '/' . ($t + 1) : $t - 1 . '/' . $t;
        @endphp

        <p style="font-size: 0.9375rem; margin: 0.75rem 0 0 0; color: #4b5563;">
            MTsN 3 Tulungagung <br>
            {{-- HAPUS HARDCODE 2026/2027, GANTI DENGAN VARIABEL $tapel --}}
            Tahun Pelajaran {{ $tapel }}
        </p>
    </div>

    {{-- Tanda Tangan: Mode Pelatih Merangkap Pembina (3 Orang) --}}
    @if ($record->isPelatihMerangkapPembina())
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Disusun dan Diperiksa oleh,<br>
                            Pelatih / Pembina Ekstrakurikuler
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->pelatih()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                        {!! $signatureImg($pelatihTtd, $pembinaApproved, -4) !!}

                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Disetujui oleh,<br>
                            Wakil Kepala Madrasah<br>
                            Bidang Kesiswaan
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->wakaKesiswaan()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                        {!! $signatureImg($wakaTtd, $wakaApproved, 4) !!}

                    </div>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
            <tr>
                <td style="text-align:center; font-size:0.9375rem; line-height:1.5;">
                    <div style="position:relative;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Mengetahui,<br>
                            Kepala MTsN 3 Tulungagung
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->kepalaMadrasah()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                        {!! $signatureImg($kepalaTtd, $kepalaApproved, -2) !!}

                    </div>
                </td>
            </tr>
        </table>

        {{-- Tanda Tangan: Mode Normal (4 Orang) --}}
    @else
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Disusun oleh,<br>
                            Pelatih
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->pelatih()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Diperiksa oleh,<br>
                            Pembina Ekstrakurikuler
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->pembina()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                        {!! $signatureImg($pembinaTtd, $pembinaApproved, 4) !!}

                    </div>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 3rem; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Disetujui oleh,<br>
                            Wakil Kepala Madrasah<br>
                            Bidang Kesiswaan
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->wakaKesiswaan()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                        {!! $signatureImg($wakaTtd, $wakaApproved, -4) !!}

                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding: 0 1rem;">
                    <div style="position:relative; text-align:center; font-size:0.9375rem; line-height:1.5;">

                        <div
                            style="position:relative; z-index:1; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            Mengetahui,<br>
                            Kepala MTsN 3 Tulungagung
                        </div>

                        <div style="height:70px;"></div>

                        <div style="position:relative; z-index:1;">
                            <strong style="text-decoration: underline;">
                                {{ $record->kepalaMadrasah()?->pegawai?->nama ?? '........................................' }}
                            </strong>
                        </div>

                        {!! $signatureImg($kepalaTtd, $kepalaApproved, 3) !!}

                    </div>
                </td>
            </tr>
        </table>
    @endif

</div>
