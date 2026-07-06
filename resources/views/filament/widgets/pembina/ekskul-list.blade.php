<x-filament-widgets::widget>
    {{-- Section Header --}}
    <x-filament::section heading="Ekstrakurikuler yang Saya Ampu" icon="heroicon-o-building-library" icon-color="primary">
        {{-- Grid Container untuk Card --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">

            @forelse($ekskulData as $item)
                {{-- Card Item --}}
                <div
                    style="
                    background: #ffffff;
                    border: 1px solid #e5e7eb;
                    border-radius: 0.75rem;
                    padding: 1.25rem;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                ">

                    {{-- Header: Nama & Detail --}}
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #111827;">
                                {{ $item['ekskul']->nama }}
                            </h3>
                            <div style="margin-top: 0.5rem;">
                                <x-filament::badge color="success">
                                    {{ $item['jumlahAnggota'] }} Anggota Aktif
                                </x-filament::badge>
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
                            Tim Pengampu
                        </p>

                        @if ($item['tim']->isNotEmpty())
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                @foreach ($item['tim'] as $anggota)
                                    <div
                                        style="
                                        display: flex; align-items: center; gap: 0.5rem;
                                        border: 1px solid #f3f4f6; border-radius: 0.5rem; padding: 0.35rem 0.75rem; background: #f9fafb;
                                    ">
                                        {{-- Inisial --}}
                                        <div
                                            style="
                                            display: flex; align-items: center; justify-content: center;
                                            width: 1.5rem; height: 1.5rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 700; color: #ffffff;
                                            background-color: {{ $anggota->role === 'pembina' ? '#10b981' : '#0ea5e9' }};
                                        ">
                                            {{ substr($anggota->pegawai?->nama ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <p style="margin: 0; font-size: 0.75rem; font-weight: 600; color: #374151;">
                                                {{ $anggota->pegawai?->nama ?? '-' }}
                                            </p>
                                            <p
                                                style="margin: 0; font-size: 0.6rem; font-weight: 500; color: #6b7280; text-transform: uppercase;">
                                                {{ $anggota->role }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="margin: 0; font-size: 0.75rem; font-style: italic; color: #9ca3af;">
                                Belum ada rekan pengampu lain.
                            </p>
                        @endif
                    </div>
                </div>
            @empty
                <p
                    style="grid-column: span 1 / span 1; padding: 2rem; text-align: center; color: #9ca3af; font-size: 0.875rem;">
                    Anda belum diampu ke ekskul manapun.
                </p>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
