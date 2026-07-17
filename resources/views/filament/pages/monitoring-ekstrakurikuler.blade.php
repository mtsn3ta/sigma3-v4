<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Filter Section --}}
        <x-filament::section>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Tahun
                        Ajaran</label>
                    <select
                        style="display: block; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem;"
                        wire:model="tahunAjaranId">
                        @foreach ($this->daftarTahunAjaran as $id => $nama)
                            <option value="{{ $id }}">{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        style="display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: #374151;">Semester</label>
                    <select
                        style="display: block; width: 100%; border-radius: 0.5rem; border: 1px solid #d1d5db; padding: 0.5rem 0.75rem;"
                        wire:model="semester">
                        <option value="ganjil">Ganjil (Juli - Desember)</option>
                        <option value="genap">Genap (Januari - Juni)</option>
                    </select>
                </div>
                <div style="display: flex;">
                    <x-filament::button wire:click="tampilkan" style="width: 100%;">Tampilkan
                        Monitoring</x-filament::button>
                </div>
            </div>
        </x-filament::section>

        {{-- Informasi Ringkas --}}
        <x-filament::section>
            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 0.85rem; color: #6b7280; display: block;">Tahun Ajaran</span>
                    <strong style="font-size: 1rem;">{{ $this->daftarTahunAjaran[$tahunAjaranId] ?? '-' }}</strong>
                </div>
                <div>
                    <span style="font-size: 0.85rem; color: #6b7280; display: block;">Semester</span>
                    <strong style="font-size: 1rem;">{{ ucfirst($semester) }}</strong>
                </div>
            </div>
        </x-filament::section>

        {{-- Statistik Widget --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            @php
                $stats = [
                    ['label' => 'Ekstrakurikuler', 'value' => $totalEkskul, 'color' => '#2563eb'],
                    ['label' => 'Pembina', 'value' => $totalPembina, 'color' => '#059669'],
                    ['label' => 'Pelatih', 'value' => $totalPelatih, 'color' => '#d97706'],
                    ['label' => 'Anggota', 'value' => $totalAnggota, 'color' => '#dc2626'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div
                    style="background: #fff; border: 1px solid #e5e7eb; border-radius: 1rem; padding: 1.25rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <span style="font-size: 0.85rem; color: #6b7280; font-weight: 500;">{{ $stat['label'] }}</span>
                    <div style="font-size: 2rem; font-weight: 800; color: {{ $stat['color'] }};">{{ $stat['value'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tabel Data --}}
        <x-filament::section>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem;">Daftar
                Ekstrakurikuler</h3>
            <div style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background: #fff;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead style="background: #f9fafb; border-bottom: 2px solid #f3f4f6;">
                        <tr style="color: #4b5563;">
                            <th style="padding: 0.75rem; text-align: center;">No</th>
                            <th style="padding: 0.75rem; text-align: left;">Ekstrakurikuler</th>
                            <th style="padding: 0.75rem; text-align: left;">Pembina</th>
                            <th style="padding: 0.75rem; text-align: left;">Pelatih</th>
                            <th style="padding: 0.75rem; text-align: left;">Hari</th>
                            <th style="padding: 0.75rem; text-align: center;">Anggota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.75rem; text-align: center;">{{ $loop->iteration }}</td>
                                <td style="padding: 0.75rem; font-weight: 600; color: #111827;">{{ $row->nama }}</td>
                                <td style="padding: 0.75rem;">
                                    {{ $row->pengampu->where('role', 'pembina')->pluck('pegawai.nama')->filter()->implode(', ') ?: '-' }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $row->pengampu->where('role', 'pelatih')->pluck('pegawai.nama')->filter()->implode(', ') ?: '-' }}
                                </td>
                                <td style="padding: 0.75rem;">{{ collect($row->jadwal ?? [])->implode(', ') ?: '-' }}
                                </td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    <x-filament::badge
                                        color="primary">{{ $row->anggotaEkskul?->count() ?? 0 }}</x-filament::badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 2rem; text-align: center; color: #6b7280;">Tidak ada
                                    data ekstrakurikuler.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
