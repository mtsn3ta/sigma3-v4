<div style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">

    {{-- Detail Sesi (Glass Card) --}}
    <x-filament::section>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            @php
                $details = [
                    'Ekstrakurikuler' => $sesi->ekstrakurikuler->nama,
                    'Hari / Tanggal' => \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('l, d F Y'),
                    'Jam' => $sesi->jam_mulai . ' - ' . $sesi->jam_selesai,
                    'Pelatih' => $sesi->pelatih?->nama ?? '-',
                ];
            @endphp
            @foreach ($details as $label => $value)
                <div>
                    <div
                        style="font-size: 0.8rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.025em; margin-bottom: 0.25rem;">
                        {{ $label }}</div>
                    <div style="font-weight: 600; color: #111827;">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 1.5rem; border-top: 1px solid #f3f4f6; padding-top: 1rem;">
            <div style="font-size: 0.8rem; color: #6b7280; margin-bottom: 0.25rem;">Program Latihan</div>
            <div style="font-weight: 500; color: #374151; font-style: italic;">"{{ $sesi->program_latihan }}"</div>
        </div>
    </x-filament::section>

    {{-- Daftar Anggota (Tabel Gaya Modern) --}}
    <x-filament::section>
        <x-slot name="heading">Daftar Anggota</x-slot>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            @foreach ($anggota as $index => $item)
                <div
                    style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; background: #fff; transition: all 0.2s;">

                    <div style="font-weight: 500; color: #111827;">{{ $item['nama'] }}</div>

                    @if ($item['rfid'])
                        <x-filament::badge color="success">RFID ✓</x-filament::badge>
                    @else
                        <div style="display: flex; gap: 1.5rem; align-items: center;">
                            <label
                                style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #059669;">
                                <input type="radio" wire:model="anggota.{{ $index }}.status" value="hadir"
                                    style="accent-color: #059669;">
                                Hadir
                            </label>
                            <label
                                style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: #dc2626;">
                                <input type="radio" wire:model="anggota.{{ $index }}.status"
                                    value="tidak_hadir" style="accent-color: #dc2626;">
                                Tidak Hadir
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Tombol Submit --}}
    <div style="display: flex; justify-content: flex-end; padding-top: 1rem;">
        <x-filament::button wire:click="submit" color="success" size="lg" icon="heroicon-m-check-circle">
            Simpan Absensi
        </x-filament::button>
    </div>

</div>
