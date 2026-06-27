@php

    use App\Support\LaporanBulananHelper;

    $proposals = LaporanBulananHelper::proposal($record);

@endphp

<h3 class="text-lg font-semibold mb-3">
    3.1 Proposal Kegiatan
</h3>

<table class="min-w-full border text-sm">

    <thead>

        <tr>

            <th class="border px-2 py-2">No</th>

            <th class="border px-2 py-2">Nama Kegiatan</th>

            <th class="border px-2 py-2">Jenis</th>

            <th class="border px-2 py-2">Tanggal</th>

            <th class="border px-2 py-2">Status</th>

        </tr>

    </thead>

    <tbody>

        @forelse($proposals as $proposal)
            <tr>

                <td class="border px-2 py-2">
                    {{ $loop->iteration }}
                </td>

                <td class="border px-2 py-2">
                    {{ $proposal->nama_kegiatan }}
                </td>

                <td class="border px-2 py-2">
                    {{ $proposal->jenis_kegiatan_label }}
                </td>

                <td class="border px-2 py-2">
                    {{ $proposal->tanggal_mulai->translatedFormat('d M Y') }}
                </td>

                <td class="border px-2 py-2">
                    {{ $proposal->status_label }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="border py-3 text-center">

                    Tidak ada proposal kegiatan.

                </td>

            </tr>
        @endforelse

    </tbody>

</table>
