<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- SECTION: EKSTRAKURIKULER AKTIF --}}
        <x-filament::section>
            <x-slot name="heading">
                Ekstrakurikuler Aktif
            </x-slot>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                @forelse($this->siswa->anggotaEkskul->where('status', 'aktif') as $item)
                    <div style="padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;">
                        <h4 style="font-weight: 700; color: #111827;">{{ $item->ekstrakurikuler->nama }}</h4>
                        <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">
                            Terdaftar sejak:
                            {{ \Carbon\Carbon::parse($item->tanggal_daftar)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                @empty
                    <p style="color: #6b7280; font-style: italic;">Anda belum mengikuti ekstrakurikuler.</p>
                @endforelse
            </div>
        </x-filament::section>

        {{-- SECTION: RIWAYAT EKSTRAKURIKULER --}}
        <x-filament::section>
            <x-slot name="heading">
                Riwayat Ekstrakurikuler
            </x-slot>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f3f4f6;">
                            <th style="padding: 0.75rem;">Nama Ekskul</th>
                            <th style="padding: 0.75rem;">Tahun Ajaran</th>
                            <th style="padding: 0.75rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->siswa->anggotaEkskul->where('status', 'nonaktif') as $item)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.75rem;">{{ $item->ekstrakurikuler->nama }}</td>
                                <td style="padding: 0.75rem;">{{ $item->tahunAjaran->tahun }}</td>
                                <td style="padding: 0.75rem;">
                                    <span
                                        style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem;">
                                        Nonaktif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding: 0.75rem; color: #6b7280; font-style: italic;">
                                    Belum ada riwayat kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

    </div>
</x-filament-panels::page>
