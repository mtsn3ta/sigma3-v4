<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Filter Section --}}
        <x-filament::section>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: flex-end;">

                <div>
                    <label
                        style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">
                        Tanggal Mulai
                    </label>
                    <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                        wire:model="tanggalMulai">
                </div>

                <div>
                    <label
                        style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">
                        Tanggal Selesai
                    </label>
                    <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                        wire:model="tanggalSelesai">
                </div>

                <div>
                    <label
                        style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">
                        Filter Kelas
                    </label>
                    <select class="fi-select block w-full rounded-lg border-gray-300" wire:model="kelasId">
                        <option value="">Semua Kelas</option>
                        @foreach ($this->daftarKelas as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex;">
                    <x-filament::button wire:click="tampilkan" style="width: 100%;">
                        Tampilkan Monitoring
                    </x-filament::button>
                </div>

            </div>
        </x-filament::section>

        {{-- Informasi Header --}}
        <x-filament::section>
            <div style="display: flex; flex-wrap: wrap; gap: 2rem;">
                <div style="flex: 1; min-width: 250px;">
                    <span style="font-size: 0.875rem; color: #6b7280; display: block; margin-bottom: 0.25rem;">Periode
                        Monitoring</span>
                    <strong style="font-size: 1.125rem; color: #111827;">
                        @if ($tanggalMulai && $tanggalSelesai)
                            {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d M Y') }}
                            <span style="color: #9ca3af; font-weight: 400; margin: 0 0.5rem;">s/d</span>
                            {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d M Y') }}
                        @else
                            -
                        @endif
                    </strong>

                </div>

                <div style="flex: 1; min-width: 250px;">
                    <span style="font-size: 0.875rem; color: #6b7280; display: block; margin-bottom: 0.25rem;">Kelas
                        Terpilih</span>
                    <strong style="font-size: 1.125rem; color: #111827;">
                        {{ $this->kelas?->nama_kelas ?? 'Semua Kelas' }}
                    </strong>
                </div>
            </div>
        </x-filament::section>

        {{-- Timeline Data --}}
        @forelse($timeline as $hari)
            <x-filament::section>

                <div
                    style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem;">

                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">
                        {{-- PERBAIKAN: Paksa objek Carbon tanggal harian menggunakan lokalisasi Bahasa Indonesia --}}
                        {{ $hari['tanggal']->locale('id')->translatedFormat('l, d F Y') }}
                    </h3>


                    <div style="display: flex; gap: 0.75rem; font-size: 0.75rem; font-weight: 600;">
                        <div
                            style="background-color: rgba(5, 150, 105, 0.1); color: #059669; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid rgba(5, 150, 105, 0.2);">
                            Mengajar: {{ $hari['totalMengajar'] }}
                        </div>
                        <div
                            style="background-color: rgba(217, 119, 6, 0.1); color: #d97706; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid rgba(217, 119, 6, 0.2);">
                            Izin: {{ $hari['totalIzin'] }}
                        </div>
                        <div
                            style="background-color: rgba(220, 38, 38, 0.1); color: #dc2626; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid rgba(220, 38, 38, 0.2);">
                            Kosong: {{ $hari['totalKosong'] }}
                        </div>
                    </div>

                </div>

                <div
                    style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: rgba(255, 255, 255, 0.5); backdrop-filter: blur(12px);">
                    <table style="width: 100%; font-size: 0.875rem; text-align: left; border-collapse: collapse;">
                        <thead style="background-color: rgba(249, 250, 251, 0.7); border-bottom: 1px solid #e5e7eb;">
                            <tr>
                                <th
                                    style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; width: 60px; text-align: center;">
                                    Jam</th>
                                <th
                                    style="padding: 0.75rem 1rem; font-weight: 600; color: #374151; text-align: center;">
                                    Status</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Guru</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Mapel</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Materi / Tugas</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Tidak Hadir</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #374151;">Catatan</th>
                            </tr>
                        </thead>

                        <tbody style="color: #4b5563;">
                            @foreach ($hari['timeline'] as $row)
                                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                                    onmouseover="this.style.backgroundColor='#f9fafb'"
                                    onmouseout="this.style.backgroundColor='transparent'">

                                    <td
                                        style="padding: 0.75rem 1rem; text-align: center; font-weight: 700; color: #111827;">
                                        {{ $row['jam'] }}
                                    </td>

                                    <td style="padding: 0.75rem 1rem; text-align: center;">
                                        @switch($row['status'])
                                            @case('mengajar')
                                                <x-filament::badge color="success">Mengajar</x-filament::badge>
                                            @break

                                            @case('izin')
                                                <x-filament::badge color="warning">Izin</x-filament::badge>
                                            @break

                                            @default
                                                <x-filament::badge color="danger">Kosong</x-filament::badge>
                                        @endswitch
                                    </td>

                                    <td style="padding: 0.75rem 1rem; font-weight: 500; color: #111827;">
                                        {{ $row['guru'] ?: '-' }}
                                    </td>

                                    <td style="padding: 0.75rem 1rem;">
                                        {{ $row['mapel'] ?: '-' }}
                                    </td>

                                    <td style="padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8125rem;">
                                        {{ $row['materi'] ?: '-' }}
                                    </td>

                                    <td
                                        style="padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8125rem; color: #dc2626;">
                                        {{ $row['tidak_hadir'] ?: '-' }}
                                    </td>

                                    <td
                                        style="padding: 0.75rem 1rem; white-space: pre-wrap; font-size: 0.8125rem; font-style: italic;">
                                        {{ $row['catatan'] ?: '-' }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </x-filament::section>

            @empty
                <x-filament::section>
                    <div style="text-align: center; padding: 4rem 1rem;">
                        <div
                            style="display: inline-flex; justify-content: center; align-items: center; width: 4rem; height: 4rem; border-radius: 9999px; background-color: #f3f4f6; margin-bottom: 1rem;">
                            <svg style="width: 2rem; height: 2rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin-bottom: 0.5rem;">Belum ada
                            data monitoring</h3>
                        <p style="font-size: 0.875rem; color: #6b7280;">Silakan pilih tanggal dan kelas, lalu klik
                            'Tampilkan Monitoring'.</p>
                    </div>
                </x-filament::section>
            @endforelse

        </div>
    </x-filament-panels::page>
