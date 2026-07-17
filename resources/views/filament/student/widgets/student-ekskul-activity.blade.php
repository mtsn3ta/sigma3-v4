<x-filament-widgets::widget>
    <div
        style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.5rem;
    ">

        {{-- ========================================================================= --}}
        {{-- WIDGET: JADWAL TERDEKAT --}}
        {{-- ========================================================================= --}}
        <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">
            <div
                style="
                padding: 1.5rem;
                background: #ffffff;
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
                height: 100%;
            ">
                {{-- Header --}}
                <div
                    style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                    <div
                        style="
                        display: flex; align-items: center; justify-content: center;
                        width: 36px; height: 36px; background: #eff6ff; border-radius: 0.5rem; color: #3b82f6;
                        box-shadow: 0 2px 10px rgba(59, 130, 246, 0.15);
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                    </div>
                    <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0;">
                        Jadwal Terdekat
                    </h2>
                </div>

                {{-- List --}}
                <div style="display: flex; flex-direction: column;">
                    @forelse ($jadwalTerdekat as $sesi)
                        <div
                            style="
                            display: flex; justify-content: space-between; align-items: center;
                            padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;
                            {{ $loop->last ? 'border-bottom: none; padding-bottom: 0;' : '' }}
                        ">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                {{-- Mini Calendar Icon --}}
                                <div
                                    style="
                                    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem;
                                    padding: 0.35rem 0.5rem; text-align: center; min-width: 55px;
                                ">
                                    <div
                                        style="font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
                                        {{ $sesi->tanggal->translatedFormat('D') }}
                                    </div>
                                    <div style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">
                                        {{ $sesi->tanggal->translatedFormat('d M') }}
                                    </div>
                                </div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">
                                    {{ $sesi->ekstrakurikuler?->nama ?? '-' }}
                                </div>
                            </div>

                            {{-- Time Pill --}}
                            <div
                                style="
                                background: #eff6ff; color: #2563eb; padding: 0.25rem 0.75rem;
                                border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
                                border: 1px solid #dbeafe; font-family: ui-monospace, monospace;
                            ">
                                {{ \Illuminate\Support\Carbon::parse($sesi->waktu_mulai)->format('H:i') }}
                            </div>
                        </div>
                    @empty
                        <div
                            style="
                            padding: 2rem 1rem; text-align: center; background: #f8fafc;
                            border: 2px dashed #e2e8f0; border-radius: 0.75rem;
                            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                style="width: 32px; height: 32px; color: #94a3b8;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <div style="color: #64748b; font-size: 0.85rem; font-weight: 500;">
                                Belum ada jadwal terdekat.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </x-filament::section>

        {{-- ========================================================================= --}}
        {{-- WIDGET: ABSENSI TERAKHIR --}}
        {{-- ========================================================================= --}}
        <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">
            <div
                style="
                padding: 1.5rem;
                background: #ffffff;
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
                height: 100%;
            ">
                {{-- Header --}}
                <div
                    style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                    <div
                        style="
                        display: flex; align-items: center; justify-content: center;
                        width: 36px; height: 36px; background: #ecfdf5; border-radius: 0.5rem; color: #10b981;
                        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.15);
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 0 1 9 9v.375M10.125 2.25A3.375 3.375 0 0 1 13.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 0 1 3.375 3.375M9 15l2.25 2.25L15 12" />
                        </svg>
                    </div>
                    <h2 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin: 0;">
                        Absensi Ekskul Terakhir
                    </h2>
                </div>

                {{-- List --}}
                <div style="display: flex; flex-direction: column;">
                    @forelse ($absensiTerakhir as $absen)
                        <div
                            style="
                            display: flex; justify-content: space-between; align-items: center;
                            padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;
                            {{ $loop->last ? 'border-bottom: none; padding-bottom: 0;' : '' }}
                        ">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                {{-- Mini Calendar Icon --}}
                                <div
                                    style="
                                    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem;
                                    padding: 0.35rem 0.5rem; text-align: center; min-width: 55px;
                                ">
                                    <div
                                        style="font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
                                        {{ $absen->waktu_scan?->translatedFormat('D') ?? '-' }}
                                    </div>
                                    <div style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">
                                        {{ $absen->waktu_scan?->translatedFormat('d M') ?? '-' }}
                                    </div>
                                </div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">
                                    {{ $absen->sesiKegiatan?->ekstrakurikuler?->nama ?? '-' }}
                                </div>
                            </div>

                            {{-- Time Pill --}}
                            <div
                                style="
                                background: #ecfdf5; color: #059669; padding: 0.25rem 0.75rem;
                                border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
                                border: 1px solid #a7f3d0; font-family: ui-monospace, monospace;
                            ">
                                {{ $absen->waktu_scan?->format('H:i') ?? '--:--' }}
                            </div>
                        </div>
                    @empty
                        <div
                            style="
                            padding: 2rem 1rem; text-align: center; background: #f8fafc;
                            border: 2px dashed #e2e8f0; border-radius: 0.75rem;
                            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                style="width: 32px; height: 32px; color: #94a3b8;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div style="color: #64748b; font-size: 0.85rem; font-weight: 500;">
                                Belum ada riwayat absensi.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </x-filament::section>

    </div>
</x-filament-widgets::widget>
