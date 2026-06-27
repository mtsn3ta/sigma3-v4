@php

    use App\Support\LaporanBulananHelper;

    $kegiatan = LaporanBulananHelper::kegiatan($record);

@endphp

<h3 class="text-lg font-semibold mt-6 mb-3">
    3.2 Laporan Kegiatan
</h3>

<table class="min-w-full border text-sm">

    <thead>

        <tr>

            <th class="border px-2 py-2">No</th>

            <th class="border px-2 py-2">Kegiatan</th>

            <th class="border px-2 py-2">Tanggal</th>

        </tr>

    </thead>

    <tbody>

        @forelse($kegiatan as $laporan)
            <tr>

                <td class="border px-2 py-2">

                    {{ $loop->iteration }}

                </td>

                <td class="border px-2 py-2">

                    {{ $laporan->proposal?->nama_kegiatan }}

                </td>

                <td class="border px-2 py-2">

                    {{ $laporan->proposal?->tanggal_mulai?->translatedFormat('d M Y') }}

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3" class="border py-3 text-center">

                    Belum ada laporan kegiatan.

                </td>

            </tr>
        @endforelse

    </tbody>

</table>
