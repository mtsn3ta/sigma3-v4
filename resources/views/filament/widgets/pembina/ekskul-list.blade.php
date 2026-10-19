<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm backdrop-blur-xl sm:p-6 dark:border-slate-800 dark:bg-slate-900/90">
        {{-- Emerald Glow Effect --}}
        <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-emerald-500/10 blur-3xl"></div>

        {{-- Header Section --}}
        <div class="relative z-10 mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 shadow-sm ring-1 ring-emerald-500/20 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <x-filament::icon icon="heroicon-o-building-library" class="h-5 w-5" />
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 sm:text-lg dark:text-white">Ekstrakurikuler yang Saya Ampu</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Kelola kegiatan latihan dan daftar anggota aktif.</p>
                </div>
            </div>

            <a href="/admin/sesi-kegiatans/create"
                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <x-filament::icon icon="heroicon-m-plus" class="h-4 w-4" />
                <span>+ Buat Sesi Baru</span>
            </a>
        </div>

        {{-- Grid Container --}}
        <div class="relative z-10 grid grid-cols-1 gap-4 md:grid-cols-2">
            @forelse($ekskulData as $item)
                <div class="flex flex-col justify-between rounded-xl border border-slate-200/70 bg-gradient-to-b from-white to-slate-50/50 p-4 shadow-sm transition hover:border-emerald-300 hover:shadow-md dark:border-slate-800 dark:from-slate-900 dark:to-slate-900/50 dark:hover:border-emerald-700">
                    <div>
                        {{-- Header Card --}}
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                    {{ $item['ekskul']->nama }}
                                </h3>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 dark:bg-emerald-950/50 dark:text-emerald-300">
                                        <x-filament::icon icon="heroicon-m-user-group" class="h-3 w-3" />
                                        {{ $item['jumlahAnggota'] }} Anggota Aktif
                                    </span>
                                </div>
                            </div>

                            <a href="/admin/ekstrakurikulers/{{ $item['ekskul']->id }}/edit"
                                class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                Detail →
                            </a>
                        </div>

                        {{-- Tim Pengampu --}}
                        <div class="mt-3.5 border-t border-slate-100 pt-3 dark:border-slate-800">
                            <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tim Pengampu</p>
                            @if ($item['tim']->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($item['tim'] as $anggota)
                                        <div class="inline-flex items-center gap-1.5 rounded-lg border border-slate-100 bg-white px-2 py-1 shadow-2xs dark:border-slate-800 dark:bg-slate-800/80">
                                            <div class="flex h-5 w-5 items-center justify-center rounded-full text-[10px] font-bold text-white {{ $anggota->role === 'pembina' ? 'bg-emerald-600' : 'bg-sky-600' }}">
                                                {{ substr($anggota->pegawai?->nama ?? 'U', 0, 1) }}
                                            </div>
                                            <span class="text-xs font-medium text-slate-700 dark:text-slate-200">{{ $anggota->pegawai?->nama ?? '-' }}</span>
                                            <span class="rounded bg-slate-100 px-1 py-0.2 text-[9px] font-bold uppercase text-slate-500 dark:bg-slate-700 dark:text-slate-300">{{ $anggota->role }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs italic text-slate-400">Belum ada rekan pengampu.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Action Footer --}}
                    <div class="mt-4 flex items-center justify-between gap-2 border-t border-slate-100 pt-3 dark:border-slate-800">
                        <a href="/admin/sesi-kegiatans"
                            class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 transition hover:text-emerald-700 dark:text-emerald-400">
                            <x-filament::icon icon="heroicon-m-clipboard-document-check" class="h-4 w-4" />
                            Log Sesi & Absensi
                        </a>

                        <a href="/admin/sesi-kegiatans/create"
                            class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100 dark:bg-emerald-950/60 dark:text-emerald-300">
                            <x-filament::icon icon="heroicon-m-plus" class="h-3.5 w-3.5" />
                            Sesi
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-slate-200 p-8 text-center text-slate-400 dark:border-slate-800">
                    <x-filament::icon icon="heroicon-o-information-circle" class="mx-auto h-8 w-8 text-slate-300" />
                    <p class="mt-2 text-sm font-medium">Tidak ada ekstrakurikuler yang diampu saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-widgets::widget>
