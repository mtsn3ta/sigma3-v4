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

                    @foreach ($this->daftarKelas as $id => $nama)
                        <option value="{{ $id }}">
                            {{ $nama }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="flex items-end gap-2">

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

    {{-- Timeline --}}
    @forelse($timeline as $hari)

        <x-filament::section class="mt-6">

            <h3 class="text-lg font-bold mb-3">

                {{ $hari['tanggal']->translatedFormat('l, d F Y') }}

            </h3>

            <div class="mb-4 flex gap-6 text-sm">

                <span class="text-success-600">

                    Mengajar :
                    {{ $hari['totalMengajar'] }}

                </span>

                <span class="text-warning-600">

                    Izin :
                    {{ $hari['totalIzin'] }}

                </span>

                <span class="text-danger-600">

                    Kosong :
                    {{ $hari['totalKosong'] }}

                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="border px-3 py-2">Jam</th>

                            <th class="border px-3 py-2">Status</th>

                            <th class="border px-3 py-2">Guru</th>

                            <th class="border px-3 py-2">Mapel</th>

                            <th class="border px-3 py-2">Materi / Tugas</th>

                            <th class="border px-3 py-2">Tidak Hadir</th>

                            <th class="border px-3 py-2">Catatan</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach ($hari['timeline'] as $row)
                            <tr>

                                <td class="border px-3 py-2">

                                    {{ $row['jam'] }}

                                </td>

                                <td class="border px-3 py-2">

                                    @switch($row['status'])
                                        @case('mengajar')
                                            <x-filament::badge color="success">

                                                Mengajar

                                            </x-filament::badge>
                                        @break

                                        @case('izin')
                                            <x-filament::badge color="warning">

                                                Izin

                                            </x-filament::badge>
                                        @break

                                        @default
                                            <x-filament::badge color="danger">

                                                Kosong

                                            </x-filament::badge>
                                    @endswitch

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row['guru'] }}

                                </td>

                                <td class="border px-3 py-2">

                                    {{ $row['mapel'] }}

                                </td>

                                <td class="border px-3 py-2 whitespace-pre-wrap">

                                    {{ $row['materi'] }}

                                </td>

                                <td class="border px-3 py-2 whitespace-pre-wrap">

                                    {{ $row['tidak_hadir'] }}

                                </td>

                                <td class="border px-3 py-2 whitespace-pre-wrap">

                                    {{ $row['catatan'] }}

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </x-filament::section>

        @empty

            <x-filament::section class="mt-6">

                <div class="text-center text-gray-500 py-10">

                    Tidak ada data monitoring.

                </div>

            </x-filament::section>

        @endforelse

    </x-filament-panels::page>
