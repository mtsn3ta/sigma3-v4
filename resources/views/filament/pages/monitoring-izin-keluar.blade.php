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

                    Total Izin Keluar

                </div>

                <div class="text-3xl font-bold text-primary-600">

                    {{ $total }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Belum Kembali

                </div>

                <div class="text-3xl font-bold text-danger-600">

                    {{ $totalBelumKembali }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Sudah Kembali

                </div>

                <div class="text-3xl font-bold text-success-600">

                    {{ $totalSudahKembali }}

                </div>

            </div>

        </div>

    </x-filament::section>

    @forelse($data->groupBy(fn($item) => $item->tanggal->format('Y-m-d')) as $tanggal => $rows)

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

                                Siswa

                            </th>

                            <th class="border px-3 py-2">

                                Jam Keluar

                            </th>

                            <th class="border px-3 py-2">

                                Jam Kembali

                            </th>

                            <th class="border px-3 py-2">

                                Keperluan

                            </th>

                            <th class="border px-3 py-2">

                                Petugas

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

                                    {{ $row->siswa?->nama_kelas_aktif ?? '-' }}

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row->siswa?->nama }}

                                </td>

                                <td class="border px-3 py-2 text-center">

                                    {{ $row->jam_keluar_formatted ?? '-' }}

                                </td>

                                <td class="border px-3 py-2 text-center">

                                    {{ $row->jam_kembali_formatted ?? '-' }}

                                </td>

                                <td class="border px-3 py-2 whitespace-pre-wrap">

                                    {{ $row->keperluan }}

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row->pegawai?->nama ?? '-' }}

                                </td>

                                <td class="border px-3 py-2">

                                    @if ($row->jam_kembali)
                                        <x-filament::badge color="success">

                                            Sudah Kembali

                                        </x-filament::badge>
                                    @else
                                        <x-filament::badge color="danger">

                                            Belum Kembali

                                        </x-filament::badge>
                                    @endif

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </x-filament::section>

    @empty

        <x-filament::section>

            <div class="py-10 text-center text-gray-500">

                Tidak ada data izin keluar.

            </div>

        </x-filament::section>

    @endforelse

</x-filament-panels::page>
