<x-filament-panels::page>

    {{-- Filter --}}
    <x-filament::section>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>

                <label class="text-sm font-medium">
                    Tahun Ajaran
                </label>

                <select wire:model="tahunAjaranId" class="fi-select block w-full rounded-lg border-gray-300">

                    @foreach ($this->daftarTahunAjaran as $id => $nama)
                        <option value="{{ $id }}">

                            {{ $nama }}

                        </option>
                    @endforeach

                </select>

            </div>

            <div>

                <label class="text-sm font-medium">
                    Semester
                </label>

                <select wire:model="semester" class="fi-select block w-full rounded-lg border-gray-300">

                    <option value="ganjil">

                        Ganjil (Juli - Desember)

                    </option>

                    <option value="genap">

                        Genap (Januari - Juni)

                    </option>

                </select>

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

        <div class="grid grid-cols-2 gap-4">

            <div>

                <strong>Tahun Ajaran</strong><br>

                {{ $this->daftarTahunAjaran[$tahunAjaranId] ?? '-' }}

            </div>

            <div>

                <strong>Semester</strong><br>

                @if ($semester === 'ganjil')
                    Ganjil (Juli - Desember)
                @else
                    Genap (Januari - Juni)
                @endif

            </div>

        </div>

    </x-filament::section>

    {{-- Statistik --}}
    <x-filament::section class="mt-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Ekstrakurikuler

                </div>

                <div class="text-3xl font-bold text-primary-600">

                    {{ $totalEkskul }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Pembina

                </div>

                <div class="text-3xl font-bold text-success-600">

                    {{ $totalPembina }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Pelatih

                </div>

                <div class="text-3xl font-bold text-warning-600">

                    {{ $totalPelatih }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Anggota

                </div>

                <div class="text-3xl font-bold text-danger-600">

                    {{ $totalAnggota }}

                </div>

            </div>

        </div>

    </x-filament::section>

    {{-- Tabel --}}
    <x-filament::section class="mt-6">

        <div class="overflow-x-auto">

            <table class="w-full border-collapse">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="border px-3 py-2 w-16">

                            No

                        </th>

                        <th class="border px-3 py-2">

                            Ekstrakurikuler

                        </th>

                        <th class="border px-3 py-2">

                            Pembina

                        </th>

                        <th class="border px-3 py-2">

                            Pelatih

                        </th>

                        <th class="border px-3 py-2 w-44">

                            Hari Latihan

                        </th>

                        <th class="border px-3 py-2 w-28">

                            Anggota

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $row)
                        <tr>

                            <td class="border px-3 py-2 text-center">

                                {{ $loop->iteration }}

                            </td>

                            <td class="border px-3 py-2">

                                {{ $row->nama }}

                            </td>

                            <td class="border px-3 py-2">

                                @php
                                    $pembina = $row->pengampu
                                        ->where('role', 'pembina')
                                        ->pluck('pegawai.nama')
                                        ->filter()
                                        ->implode(', ');
                                @endphp

                                {{ $pembina ?: '-' }}

                            </td>

                            <td class="border px-3 py-2">

                                @php
                                    $pelatih = $row->pengampu
                                        ->where('role', 'pelatih')
                                        ->pluck('pegawai.nama')
                                        ->filter()
                                        ->implode(', ');
                                @endphp

                                {{ $pelatih ?: '-' }}

                            </td>

                            <td class="border px-3 py-2">

                                {{ collect($row->jadwal ?? [])->implode(', ') ?: '-' }}

                            </td>

                            <td class="border px-3 py-2 text-center">

                                <x-filament::badge color="primary">

                                    {{ $row->anggotaEkskul?->count() ?? 0 }}

                                </x-filament::badge>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="border px-3 py-10 text-center text-gray-500">

                                Tidak ada data ekstrakurikuler.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </x-filament::section>

</x-filament-panels::page>
