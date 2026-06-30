<x-filament-panels::page>

    {{-- Filter --}}
    <x-filament::section>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>

                <label class="text-sm font-medium">
                    Tanggal Mulai
                </label>

                <input type="date" class="fi-input block w-full rounded-lg border-gray-300" wire:model="tanggalMulai">

            </div>

            <div>

                <label class="text-sm font-medium">
                    Tanggal Selesai
                </label>

                <input type="date" class="fi-input block w-full rounded-lg border-gray-300" wire:model="tanggalSelesai">

            </div>

            <div class="flex items-end">

                <x-filament::button wire:click="tampilkan">

                    Tampilkan Monitoring

                </x-filament::button>

            </div>

        </div>

    </x-filament::section>

    {{-- Informasi --}}
    <x-filament::section class="mt-6">

        <strong>Periode</strong><br>

        @if ($tanggalMulai && $tanggalSelesai)
            {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}

            s/d

            {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}
        @else
            -
        @endif

    </x-filament::section>

    {{-- Statistik --}}
    <x-filament::section class="mt-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">
                    Baru
                </div>

                <div class="text-3xl font-bold text-danger-600">

                    {{ $totalBaru }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">
                    Disampaikan
                </div>

                <div class="text-3xl font-bold text-warning-600">

                    {{ $totalDisampaikan }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">
                    Selesai
                </div>

                <div class="text-3xl font-bold text-success-600">

                    {{ $totalSelesai }}

                </div>

            </div>

        </div>

    </x-filament::section>

    @forelse($data->groupBy(fn ($item) => $item->tanggal->format('Y-m-d')) as $tanggal => $rows)

        <x-filament::section class="mt-6">

            <h3 class="text-lg font-bold mb-4">

                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}

            </h3>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="border px-3 py-2 w-16">
                                No
                            </th>

                            <th class="border px-3 py-2">
                                Kelas
                            </th>

                            <th class="border px-3 py-2">
                                Jam Ke
                            </th>

                            <th class="border px-3 py-2">
                                Guru
                            </th>

                            <th class="border px-3 py-2">
                                Isi Tugas
                            </th>

                            <th class="border px-3 py-2">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($rows as $row)
                            <tr>

                                <td class="border px-3 py-2 text-center">

                                    {{ $loop->iteration }}

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row->kelas?->nama_kelas }}

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row->jam_ke }}

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row->guru?->nama }}

                                </td>

                                <td class="border px-3 py-2 whitespace-pre-wrap">

                                    {{ $row->isi_tugas }}

                                </td>

                                <td class="border px-3 py-2">

                                    @switch($row->status)
                                        @case('baru')
                                            <x-filament::badge color="danger">

                                                Baru

                                            </x-filament::badge>
                                        @break

                                        @case('disampaikan')
                                            <x-filament::badge color="warning">

                                                Disampaikan

                                            </x-filament::badge>
                                        @break

                                        @case('selesai')
                                            <x-filament::badge color="success">

                                                Selesai

                                            </x-filament::badge>
                                        @break
                                    @endswitch

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </x-filament::section>

        @empty

            <x-filament::section>

                <div class="text-center text-gray-500 py-10">

                    Tidak ada data tugas guru izin.

                </div>

            </x-filament::section>

        @endforelse

    </x-filament-panels::page>
