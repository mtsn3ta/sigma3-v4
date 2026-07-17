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
                    width: 36px; height: 36px; background: #fef9c3; border-radius: 0.5rem; color: #ca8a04;
                    box-shadow: 0 2px 10px rgba(234, 179, 8, 0.15);
                ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                    </svg>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">
                    Prestasi Saya
                </h2>
            </div>

            {{-- List Container --}}
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($prestasi as $item)
                    {{-- Item Card --}}
                    <div
                        style="
                        display: flex; gap: 1rem; padding: 1.25rem; background: #f8fafc;
                        border: 1px solid #f1f5f9; border-radius: 0.75rem;
                    ">
                        {{-- Medal Icon --}}
                        <div
                            style="
                            flex-shrink: 0; width: 42px; height: 42px; border-radius: 50%;
                            background: #ffffff; border: 1px solid #e5e7eb;
                            display: flex; align-items: center; justify-content: center; color: #f59e0b;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
                        ">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                style="width: 20px; height: 20px;">
                                <path fill-rule="evenodd"
                                    d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div style="flex: 1;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
                                <div style="font-weight: 700; color: #1e293b; font-size: 1.05rem;">
                                    {{ $item->nama_lomba }}
                                </div>
                                <div style="font-size: 0.8rem; color: #64748b; margin-top: 0.2rem;">
                                    {{ strip_tags($item->keterangan) }}
                                </div>
                                <div
                                    style="font-size: 0.8rem; color: #64748b; font-weight: 500; font-family: ui-monospace, monospace; background: #ffffff; padding: 0.2rem 0.6rem; border-radius: 0.3rem; border: 1px solid #e2e8f0;">
                                    {{ $item->tanggal?->translatedFormat('d M Y') }}
                                </div>
                            </div>

                            {{-- Badges / Pills --}}
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.85rem;">
                                {{-- Nama Prestasi (Gold) --}}
                                <span
                                    style="
    background: #fef9c3; color: #ca8a04; padding: 0.25rem 0.75rem;
    border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
    border: 1px solid #fef08a; letter-spacing: 0.025em; text-transform: uppercase;
">
                                    🏆 {{ $item->nama_prestasi }}
                                </span>

                                {{-- Jenis (Green) --}}
                                <span
                                    style="
    background: #f0fdf4; color: #15803d; padding: 0.25rem 0.75rem;
    border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
    border: 1px solid #bbf7d0; letter-spacing: 0.025em; text-transform: uppercase;
">
                                    {{ $item->jenis_label }}
                                </span>

                                {{-- Tingkat (Purple) --}}
                                <span
                                    style="
                                    background: #f3e8ff; color: #7e22ce; padding: 0.25rem 0.75rem;
                                    border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
                                    border: 1px solid #e9d5ff; letter-spacing: 0.025em; text-transform: uppercase;
                                ">
                                    {{ ucfirst($item->tingkat) }}
                                </span>

                                {{-- Ekstrakurikuler (Blue) --}}
                                @if ($item->ekstrakurikuler)
                                    <span
                                        style="
                                        background: #eff6ff; color: #2563eb; padding: 0.25rem 0.75rem;
                                        border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
                                        border: 1px solid #dbeafe; letter-spacing: 0.025em; text-transform: uppercase;
                                    ">
                                        {{ $item->ekstrakurikuler->nama }}
                                    </span>
                                @endif
                            </div>
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
                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                        <div style="color: #64748b; font-size: 0.9rem; font-weight: 500;">
                            Belum ada prestasi yang tercatat.
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
