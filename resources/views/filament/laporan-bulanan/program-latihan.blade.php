@php

    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */

    $sessions = LaporanBulananHelper::sesi($record);

@endphp

<h3 class="text-lg font-semibold mt-6 mb-3">
    2.2 Program Latihan
</h3>

<div class="overflow-x-auto">

    <table class="min-w-full border border-gray-300 text-sm">

        <thead>

            <tr class="bg-gray-100">

                <th class="border px-2 py-2" width="40">
                    No
                </th>

                <th class="border px-2 py-2" width="120">
                    Tanggal
                </th>

                <th class="border px-2 py-2" width="180">
                    Pelatih
                </th>

                <th class="border px-2 py-2">
                    Program Latihan
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($sessions as $session)
                <tr>

                    <td class="border px-2 py-2 text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="border px-2 py-2">
                        {{ \Carbon\Carbon::parse($session->tanggal)->translatedFormat('d M Y') }}
                    </td>

                    <td class="border px-2 py-2">
                        {{ $session->pelatih?->nama ?? '-' }}
                    </td>

                    <td class="border px-2 py-2">

                        {!! $session->program_latihan ?: '<span class="text-gray-500">Belum diisi</span>' !!}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="border py-4 text-center">

                        Belum ada sesi kegiatan pada periode ini.

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

</div>
