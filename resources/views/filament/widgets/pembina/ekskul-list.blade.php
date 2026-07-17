<x-filament-widgets::widget>
    {{-- Container Utama Premium --}}
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
        {{-- Pendaran Halus (Emerald Glow) --}}
        <div
            style="
            position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%; pointer-events: none; filter: blur(20px);
        ">
        </div>

        {{-- Header Section --}}
        <div
            style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 1rem; position: relative; z-index: 10;">
            <div
                style="display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #d1fae5; border-radius: 0.5rem; color: #059669; box-shadow: 0 2px 10px rgba(16, 185, 129, 0.15);">
                <x-filament::icon icon="heroicon-o-building-library" style="width: 20px; height: 20px;" />
            </div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">Ekstrakurikuler yang Saya Ampu
            </h2>
        </div>

        {{-- Grid Container --}}
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; position: relative; z-index: 10;">
            @forelse($ekskulData as $item)
                <div style="border: 1px solid #f3f4f6; border-radius: 0.75rem; padding: 1.25rem; background: #ffffff;">

                    {{-- Header Card --}}
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                        <div>
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #111827;">
                                {{ $item['ekskul']->nama }}</h3>
                            <div style="margin-top: 0.5rem;">
                                <x-filament::badge color="success">{{ $item['jumlahAnggota'] }} Anggota
                                    Aktif</x-filament::badge>
                            </div>
                        </div>
                        <x-filament::button href="/admin/ekstrakurikulers/{{ $item['ekskul']->id }}/edit" tag="a"
                            size="sm" color="gray">
                            Detail →
                        </x-filament::button>
                    </div>

                    {{-- Tim Pengampu --}}
                    <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                        <p
                            style="margin: 0 0 0.75rem 0; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: #9ca3af; letter-spacing: 0.05em;">
                            Tim Pengampu</p>

                        @if ($item['tim']->isNotEmpty())
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                @foreach ($item['tim'] as $anggota)
                                    <div
                                        style="display: flex; align-items: center; gap: 0.5rem; border: 1px solid #f3f4f6; border-radius: 0.5rem; padding: 0.35rem 0.75rem; background: #f9fafb;">
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 700; color: #ffffff; background-color: {{ $anggota->role === 'pembina' ? '#10b981' : '#0ea5e9' }};">
                                            {{ substr($anggota->pegawai?->nama ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <p style="margin: 0; font-size: 0.75rem; font-weight: 600; color: #374151;">
                                                {{ $anggota->pegawai?->nama ?? '-' }}</p>
                                            <p
                                                style="margin: 0; font-size: 0.6rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">
                                                {{ $anggota->role }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="margin: 0; font-size: 0.75rem; font-style: italic; color: #9ca3af;">Belum ada
                                rekan pengampu.</p>
                        @endif
                    </div>
                </div>
            @empty
                <p style="grid-column: 1/-1; text-align: center; color: #9ca3af; padding: 2rem;">Tidak ada ekskul yang
                    dilatih.</p>
            @endforelse
        </div>
    </div>
</x-filament-widgets::widget>
