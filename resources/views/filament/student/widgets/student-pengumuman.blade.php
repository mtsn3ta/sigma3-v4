<x-filament-widgets::widget>
    <x-filament::section style="padding: 0; background: transparent; box-shadow: none; border: none;">

        <div
            style="
            padding: 2rem;
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        ">
            {{-- Header Title --}}
            <div
                style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem;">
                <div
                    style="
                    display: flex; align-items: center; justify-content: center;
                    width: 36px; height: 36px; background: #eff6ff; border-radius: 0.5rem; color: #3b82f6;
                    box-shadow: 0 2px 10px rgba(59, 130, 246, 0.15);
                ">
                    {{-- Megaphone Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                    </svg>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">
                    Pengumuman
                </h2>
            </div>

            {{-- List Container --}}
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($pengumuman as $item)
                    {{-- Notification Card --}}
                    <div
                        style="
                        padding: 1.25rem; background: #ffffff;
                        border: 1px solid #e2e8f0; border-radius: 0.75rem;
                        border-left: 4px solid {{ $item->is_pinned ? '#f59e0b' : '#3b82f6' }};
                        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
                        display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;
                    ">
                        <div style="flex: 1;">
                            {{-- Badges / Meta --}}
                            <div
                                style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
                                @if ($item->is_pinned)
                                    <span
                                        style="
                                        background: #fef3c7; color: #b45309; padding: 0.2rem 0.6rem;
                                        border-radius: 9999px; font-size: 0.65rem; font-weight: 800;
                                        letter-spacing: 0.05em; text-transform: uppercase; border: 1px solid #fde68a;
                                    ">
                                        🔥 Penting
                                    </span>
                                @endif

                                @if ($item->ekstrakurikuler_id)
                                    <span
                                        style="
                                        background: #f1f5f9; color: #475569; padding: 0.2rem 0.6rem;
                                        border-radius: 9999px; font-size: 0.65rem; font-weight: 700;
                                        border: 1px solid #e2e8f0; letter-spacing: 0.05em; text-transform: uppercase;
                                    ">
                                        {{ $item->ekstrakurikuler?->nama }}
                                    </span>
                                @endif
                            </div>

                            {{-- Title & Content --}}
                            <h3
                                style="font-size: 1.05rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0; line-height: 1.4;">
                                {{ $item->judul }}
                            </h3>

                            <p
                                style="
                                font-size: 0.875rem; color: #64748b; margin: 0; line-height: 1.6;
                                display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
                            ">
                                {{ strip_tags($item->isi) }}
                            </p>
                        </div>

                        {{-- Date Label --}}
                        <div
                            style="
                            flex-shrink: 0; font-size: 0.75rem; color: #94a3b8; font-weight: 600;
                            font-family: ui-monospace, monospace; text-align: right;
                            background: #f8fafc; padding: 0.25rem 0.5rem; border-radius: 0.35rem; border: 1px solid #f1f5f9;
                        ">
                            {{ $item->tanggal_mulai?->translatedFormat('d M Y') }}
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div
                        style="
                        padding: 2.5rem 1rem; text-align: center; background: #f8fafc;
                        border: 2px dashed #e2e8f0; border-radius: 0.75rem;
                        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.5rem;
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width: 40px; height: 40px; color: #94a3b8;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                        </svg>
                        <div style="color: #64748b; font-size: 0.9rem; font-weight: 500;">
                            Tidak ada pengumuman aktif.
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
