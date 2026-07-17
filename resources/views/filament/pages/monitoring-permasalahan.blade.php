<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Filter Section (Menggunakan Inline CSS untuk menjamin kerapian) --}}
        <x-filament::section>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">

                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Tanggal
                        Mulai</label>
                    <input type="date"
                        style="display: block; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem;"
                        wire:model="tanggalMulai">
                </div>

                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Tanggal
                        Selesai</label>
                    <input type="date"
                        style="display: block; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem;"
                        wire:model="tanggalSelesai">
                </div>

                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Kelas</label>
                    <select
                        style="display: block; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem;"
                        wire:model="kelasId">
                        <option value="">Pilih Kelas</option>
                        @foreach (\App\Support\KelasHelper::options() as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex;">
                    <x-filament::button wire:click="tampilkan" style="width: 100%;">Tampilkan
                        Monitoring</x-filament::button>
                </div>
            </div>
        </x-filament::section>
        {{-- Informasi Section --}}
        <div
            style="margin-top: 1.5rem; padding: 1.25rem; background-color: #fff; border: 1px solid #e5e7eb; border-radius: 1rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">

                <div>
                    <span
                        style="font-size: 0.85rem; color: #6b7280; font-weight: 500; display: block; margin-bottom: 0.25rem;">Periode</span>
                    <div style="font-weight: 600; color: #111827;">
                        @if ($tanggalMulai && $tanggalSelesai)
                            {{-- PERBAIKAN 1: Paksa nama bulan periode ekspor menggunakan Bahasa Indonesia --}}
                            {{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d F Y') }}
                            <span style="color: #9ca3af; font-weight: 400; margin: 0 0.5rem;">s/d</span>
                            {{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d F Y') }}
                        @else
                            <span style="color: #9ca3af; font-style: italic;">Belum memilih periode</span>
                        @endif
                    </div>
                </div>

                <div>
                    <span
                        style="font-size: 0.85rem; color: #6b7280; font-weight: 500; display: block; margin-bottom: 0.25rem;">Kelas</span>
                    <div style="font-weight: 600; color: #111827;">
                        {{ $this->kelas?->nama_kelas ?? 'Semua Kelas' }}
                    </div>
                </div>

            </div>
        </div>
        {{-- Statistik Widget (Konsisten dengan halaman lain) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            @php
                $stats = [
                    ['label' => 'Total Permasalahan', 'value' => $totalPermasalahan, 'color' => '#dc2626'],
                    ['label' => 'Siswa Bermasalah', 'value' => $totalSiswa, 'color' => '#d97706'],
                    ['label' => 'Total Poin', 'value' => $totalPoin, 'color' => '#2563eb'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div
                    style="background: #fff; border: 1px solid #e5e7eb; border-radius: 1rem; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <span style="font-size: 0.85rem; color: #6b7280; font-weight: 500;">{{ $stat['label'] }}</span>
                    <span
                        style="font-size: 2rem; font-weight: 800; color: {{ $stat['color'] }};">{{ $stat['value'] }}</span>
                </div>
            @endforeach
        </div>

        {{-- Tabel Data --}}
        <x-filament::section>
            <div style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f9fafb; border-bottom: 2px solid #f3f4f6;">
                        <tr style="color: #4b5563;">
                            <th style="padding: 0.75rem; text-align: center;">No</th>
                            <th style="padding: 0.75rem; text-align: left;">Tanggal</th>
                            <th style="padding: 0.75rem; text-align: left;">Siswa</th>
                            <th style="padding: 0.75rem; text-align: left;">Permasalahan</th>
                            <th style="padding: 0.75rem; text-align: center;">Poin</th>
                            <th style="padding: 0.75rem; text-align: left;">Guru BK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.75rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="padding: 0.75rem; white-space: nowrap;">
                                    {{-- PERBAIKAN 2: Paksa baris tanggal kejadian di dalam tabel menggunakan Bahasa Indonesia --}}
                                    {{ $row->tanggal->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td style="padding: 0.75rem;">{{ $row->siswa?->nama ?? '-' }}</td>
                                <td style="padding: 0.75rem;">{{ $row->permasalahan }}</td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    <x-filament::badge :color="$row->poin >= 75 ? 'danger' : ($row->poin >= 50 ? 'warning' : 'success')">
                                        {{ $row->poin }}
                                    </x-filament::badge>
                                </td>
                                <td style="padding: 0.75rem;">{{ $row->pegawai?->nama ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada
                                    data permasalahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
