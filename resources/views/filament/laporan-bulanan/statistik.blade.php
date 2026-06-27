@php

    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */

    $statistik = LaporanBulananHelper::statistik($record);

@endphp

<h3 class="text-lg font-semibold mt-6 mb-3">
    2.3 Statistik Pelaksanaan
</h3>

<div class="overflow-x-auto">

    <table class="min-w-full border border-gray-300 text-sm">

        <tbody>

            <tr>
                <td class="border px-3 py-2 font-medium">
                    Jumlah Anggota Aktif
                </td>
                <td class="border px-3 py-2 text-center" width="120">
                    {{ $statistik['jumlah_anggota'] }}
                </td>
            </tr>

            <tr>
                <td class="border px-3 py-2 font-medium">
                    Jumlah Sesi Latihan
                </td>
                <td class="border px-3 py-2 text-center">
                    {{ $statistik['jumlah_sesi'] }}
                </td>
            </tr>

            <tr>
                <td class="border px-3 py-2 font-medium">
                    Persentase Kehadiran
                </td>
                <td class="border px-3 py-2 text-center">
                    {{ $statistik['persentase_kehadiran'] }}%
                </td>
            </tr>

            <tr>
                <td class="border px-3 py-2 font-medium">
                    Proposal Kegiatan
                </td>
                <td class="border px-3 py-2 text-center">
                    {{ $statistik['jumlah_proposal'] }}
                </td>
            </tr>

            <tr>
                <td class="border px-3 py-2 font-medium">
                    Laporan Kegiatan
                </td>
                <td class="border px-3 py-2 text-center">
                    {{ $statistik['jumlah_kegiatan'] }}
                </td>
            </tr>

            <tr>
                <td class="border px-3 py-2 font-medium">
                    Prestasi
                </td>
                <td class="border px-3 py-2 text-center">
                    {{ $statistik['jumlah_prestasi'] }}
                </td>
            </tr>

        </tbody>

    </table>

</div>
