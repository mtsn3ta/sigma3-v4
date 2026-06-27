@php

    use App\Support\LaporanBulananHelper;

    /** @var \App\Models\LaporanBulanan $record */

    $data = LaporanBulananHelper::anggota($record);

    $sessions = $data['sessions'];

    $members = $data['members'];

    $attendance = $data['attendance'];

@endphp

<h3 class="text-lg font-semibold mb-3">
    2.1 Rekap Kehadiran Anggota
</h3>

<div class="overflow-x-auto">

    <table class="min-w-full border border-gray-300 text-sm">

        <thead>

            <tr class="bg-gray-100">

                <th class="border px-2 py-2">No</th>

                <th class="border px-2 py-2 text-left">
                    Nama
                </th>

                @foreach ($sessions as $session)
                    <th class="border px-2 py-2">

                        {{ \Carbon\Carbon::parse($session->tanggal)->translatedFormat('d M') }}

                    </th>
                @endforeach

            </tr>

        </thead>

        <tbody>

            @forelse ($members as $member)

                <tr>

                    <td class="border px-2 py-1 text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="border px-2 py-1">
                        {{ $member->siswa->nama }}
                    </td>

                    @foreach ($sessions as $session)
                        @php

                            $hadir = $attendance->get($member->siswa_id)?->get($session->id);

                        @endphp

                        <td class="border px-2 py-1 text-center">

                            @switch($hadir?->status)
                                @case('hadir')
                                    ✔
                                @break

                                @case('izin')
                                    I
                                @break

                                @case('sakit')
                                    S
                                @break

                                @case('alpha')
                                    A
                                @break

                                @default
                                    -
                            @endswitch

                        </td>
                    @endforeach

                </tr>

                @empty

                    <tr>

                        <td colspan="{{ 2 + $sessions->count() }}" class="border py-4 text-center">

                            Belum ada anggota.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
