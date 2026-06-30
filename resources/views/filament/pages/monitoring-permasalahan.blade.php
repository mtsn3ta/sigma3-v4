<x-filament-panels::page>

    {{-- Filter --}}
    <x-filament::section>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

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

            <div>

                <label class="text-sm font-medium">
                    Kelas
                </label>

                <select class="fi-select block w-full rounded-lg border-gray-300" wire:model="kelasId">

                    <option value="">
                        Pilih Kelas
                    </option>

                    @foreach (\App\Support\KelasHelper::options() as $id => $nama)
                        <option value="{{ $id }}">

                            {{ $nama }}

                        </option>
                    @endforeach

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

                <strong>Periode</strong><br>

                @if ($tanggalMulai && $tanggalSelesai)
                    {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}

                    s/d

                    {{ \Carbon\Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}
                @else
                    -
                @endif

            </div>

            <div>

                <strong>Kelas</strong><br>

                {{ $this->kelas?->nama_kelas ?? '-' }}

            </div>

        </div>

    </x-filament::section>

    {{-- Statistik --}}
    <x-filament::section class="mt-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Total Permasalahan

                </div>

                <div class="text-3xl font-bold text-danger-600">

                    {{ $totalPermasalahan }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Siswa Bermasalah

                </div>

                <div class="text-3xl font-bold text-warning-600">

                    {{ $totalSiswa }}

                </div>

            </div>

            <div class="rounded-lg border p-4">

                <div class="text-sm text-gray-500">

                    Total Poin

                </div>

                <div class="text-3xl font-bold text-primary-600">

                    {{ $totalPoin }}

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

                        <th class="border px-3 py-2 w-40">

                            Tanggal

                        </th>

                        <th class="border px-3 py-2 w-56">

                            Siswa

                        </th>

                        <th class="border px-3 py-2">

                            Permasalahan

                        </th>

                        <th class="border px-3 py-2 w-24">

                            Poin

                        </th>

                        <th class="border px-3 py-2 w-48">

                            Guru BK

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

                                {{ $row->tanggal->translatedFormat('d M Y') }}

                            </td>

                            <td class="border px-3 py-2">

                                {{ $row->siswa?->nama }}

                            </td>

                            <td class="border px-3 py-2 whitespace-pre-wrap">

                                {{ $row->permasalahan }}

                            </td>

                            <td class="border px-3 py-2 text-center">

                                <x-filament::badge :color="$row->poin >= 75 ? 'danger' : ($row->poin >= 50 ? 'warning' : 'success')">

                                    {{ $row->poin }}

                                </x-filament::badge>

                            </td>

                            <td class="border px-3 py-2">

                                {{ $row->pegawai?->nama }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="border px-3 py-10 text-center text-gray-500">

                                Tidak ada data permasalahan.

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </x-filament::section>

</x-filament-panels::page>
