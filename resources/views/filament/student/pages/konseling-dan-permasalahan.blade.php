<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- SECTION: RIWAYAT KONSELING --}}
        <x-filament::section>
            <x-slot name="heading">
                Riwayat Konseling
            </x-slot>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                @forelse($this->siswa->konseling as $item)
                    <div style="padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <h4 style="font-weight: 700; color: #111827;">{{ $item->tanggal->translatedFormat('d F Y') }}
                            </h4>
                            <span
                                style="font-size: 0.75rem; color: #6b7280;">{{ $item->pegawai?->nama ?? 'Menunggu BK' }}</span>
                        </div>
                        <p style="font-size: 0.875rem; color: #374151; margin-top: 0.5rem;">Permasalahan:
                            {{ $item->permasalahan }}</p>
                        @if ($item->hasil_konseling !== '-')
                            <p style="font-size: 0.875rem; font-weight: 600; color: #065f46; margin-top: 0.5rem;">Hasil:
                                {{ $item->hasil_konseling }}</p>
                        @endif
                    </div>
                @empty
                    <p style="color: #6b7280; font-style: italic;">Belum ada riwayat konseling.</p>
                @endforelse
            </div>
        </x-filament::section>

        {{-- SECTION: RIWAYAT PERMASALAHAN --}}
        <x-filament::section>
            <x-slot name="heading">
                Riwayat Permasalahan
            </x-slot>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                @forelse($this->siswa->permasalahan as $item)
                    <div style="padding: 1rem; border: 1px solid #fecaca; border-radius: 0.5rem; background: #fef2f2;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <h4 style="font-weight: 700; color: #991b1b;">
                                {{ $item->tanggal->translatedFormat('d F Y') }}</h4>
                            <span
                                style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold;">
                                {{ $item->poin }} Poin
                            </span>
                        </div>
                        <p style="font-size: 0.875rem; color: #374151; margin-top: 0.5rem;">Masalah:
                            {{ $item->permasalahan }}</p>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">Penyelesaian:
                            {{ $item->penyelesaian }}</p>
                    </div>
                @empty
                    <p style="color: #6b7280; font-style: italic;">Bersyukur, tidak ada catatan permasalahan.</p>
                @endforelse
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
