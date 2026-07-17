<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Filter Section --}}
        <x-filament::section>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Tanggal
                        Mulai</label>
                    <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                        wire:model="tanggalMulai">
                </div>
                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Tanggal
                        Selesai</label>
                    <input type="date" class="fi-input block w-full rounded-lg border-gray-300"
                        wire:model="tanggalSelesai">
                </div>
                <div style="display: flex;">
                    <x-filament::button wire:click="tampilkan" style="width: 100%;">Tampilkan
                        Monitoring</x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- Statistik Widget --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            @php
                $stats = [
                    ['label' => 'Total Izin Keluar', 'value' => $total, 'color' => '#2563eb'],
                    ['label' => 'Belum Kembali', 'value' => $totalBelumKembali, 'color' => '#dc2626'],
                    ['label' => 'Sudah Kembali', 'value' => $totalSudahKembali, 'color' => '#059669'],
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

        {{-- Timeline Data --}}
        @forelse($data->groupBy(fn($item) => $item->tanggal->format('Y-m-d')) as $tanggal => $rows)
            <x-filament::section>
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 1rem;">
                    {{-- PERBAIKAN 1: Paksa format bahasa teks hari dan bulan ke Bahasa Indonesia murni --}}
                    {{ \Carbon\Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                </h3>

                <div style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background: #fff;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead style="background: #f9fafb; border-bottom: 2px solid #f3f4f6;">
                            <tr style="color: #4b5563;">
                                <th style="padding: 0.75rem; text-align: center;">No</th>
                                <th style="padding: 0.75rem; text-align: left;">Kelas</th>
                                <th style="padding: 0.75rem; text-align: left;">Siswa</th>
                                <th style="padding: 0.75rem; text-align: center;">Jam Keluar</th>
                                <th style="padding: 0.75rem; text-align: center;">Jam Kembali</th>
                                <th style="padding: 0.75rem; text-align: left;">Keperluan</th>
                                <th style="padding: 0.75rem; text-align: left;">Petugas</th>
                                <th style="padding: 0.75rem; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 0.75rem; text-align: center;">{{ $loop->iteration }}</td>
                                    <td style="padding: 0.75rem;">
                                        {{-- PERBAIKAN 2: Gunakan jalur objek kelasSiswa database murni agar nama kelas siswa baru Kelas 7 tampil utuh --}}
                                        {{ $row->siswa?->kelasSiswa?->first()?->kelas?->nama_kelas ?? '-' }}
                                    </td>
                                    <td style="padding: 0.75rem; font-weight: 500;">{{ $row->siswa?->nama }}</td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        {{ $row->jam_keluar_formatted ?? '-' }}</td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        {{ $row->jam_kembali_formatted ?? '-' }}</td>
                                    <td style="padding: 0.75rem;">{{ $row->keperluan }}</td>
                                    <td style="padding: 0.75rem;">{{ $row->pegawai?->nama ?? '-' }}</td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <x-filament::badge :color="$row->jam_kembali ? 'success' : 'danger'">
                                            {{ $row->jam_kembali ? 'Sudah Kembali' : 'Belum Kembali' }}
                                        </x-filament::badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @empty
            <x-filament::section>
                <div style="text-align: center; color: #6b7280; padding: 2rem;">Tidak ada data izin keluar.</div>
            </x-filament::section>
        @endforelse

    </div>
</x-filament-panels::page>
