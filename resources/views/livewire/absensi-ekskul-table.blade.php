@php
    $persentaseHadir = $totalAnggota > 0 ? round(($totalHadir / $totalAnggota) * 100) : 0;
    $statusColor = match($sesi->status) {
        'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800',
        'aktif' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800',
        'draft' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        'batal' => 'bg-rose-100 text-rose-800 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
    $statusLabel = match($sesi->status) {
        'selesai' => 'Selesai',
        'aktif' => 'Sesi Aktif',
        'draft' => 'Draft',
        'batal' => 'Dibatalkan',
        default => ucfirst($sesi->status),
    };
@endphp

<div class="flex flex-col gap-6 w-full pb-16">

    {{-- Breadcrumb & Quick Actions --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a href="/admin/sesi-kegiatans"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-emerald-600 transition dark:text-slate-400 dark:hover:text-emerald-400">
            <x-filament::icon icon="heroicon-m-arrow-left" class="h-4 w-4" />
            <span>Kembali ke Daftar Sesi</span>
        </a>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColor }}">
                <span class="h-2 w-2 rounded-full {{ $sesi->status === 'aktif' ? 'bg-amber-500 animate-pulse' : ($sesi->status === 'selesai' ? 'bg-emerald-500' : 'bg-slate-400') }}"></span>
                {{ $statusLabel }}
            </span>
        </div>
    </div>

    {{-- Sesi Detail Header Card --}}
    <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white/95 p-6 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/90">
        {{-- Emerald Ambient Background --}}
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-emerald-500/10 blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-100 pb-5 dark:border-slate-800">
                <div class="flex items-center gap-3.5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-md shadow-emerald-600/20">
                        <x-filament::icon icon="heroicon-o-clipboard-document-check" class="h-6 w-6" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl dark:text-white">
                                {{ $sesi->ekstrakurikuler->nama }}
                            </h1>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                            Presensi Peserta & Catatan Jurnal Sesi Kegiatan Ekstrakurikuler
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/admin/sesi-kegiatans/{{ $sesi->id }}/edit"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 transition dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                        <x-filament::icon icon="heroicon-m-pencil-square" class="h-3.5 w-3.5 text-slate-400" />
                        Edit Sesi
                    </a>
                </div>
            </div>

            {{-- Detail Metadata Grid --}}
            <div class="grid grid-cols-2 gap-4 pt-5 sm:grid-cols-4">
                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 dark:border-slate-800 dark:bg-slate-800/40">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <x-filament::icon icon="heroicon-m-calendar" class="h-3.5 w-3.5 text-emerald-500" />
                        Hari & Tanggal
                    </div>
                    <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 dark:border-slate-800 dark:bg-slate-800/40">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <x-filament::icon icon="heroicon-m-clock" class="h-3.5 w-3.5 text-emerald-500" />
                        Waktu Kegiatan
                    </div>
                    <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                        {{ $sesi->waktu_mulai ? \Carbon\Carbon::parse($sesi->waktu_mulai)->format('H:i') : '--:--' }} - {{ $sesi->waktu_selesai ? \Carbon\Carbon::parse($sesi->waktu_selesai)->format('H:i') : '--:--' }} WIB
                    </div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 dark:border-slate-800 dark:bg-slate-800/40">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <x-filament::icon icon="heroicon-m-user" class="h-3.5 w-3.5 text-emerald-500" />
                        Pelatih / Pembina
                    </div>
                    <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white truncate" title="{{ $sesi->pelatih?->nama ?? '-' }}">
                        {{ $sesi->pelatih?->nama ?? '-' }}
                    </div>
                </div>

                <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5 dark:border-slate-800 dark:bg-slate-800/40">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <x-filament::icon icon="heroicon-m-chart-bar" class="h-3.5 w-3.5 text-emerald-500" />
                        Kehadiran Siswa
                    </div>
                    <div class="mt-1 flex items-baseline gap-1.5 text-sm font-semibold text-slate-900 dark:text-white">
                        <span class="text-emerald-600 font-bold dark:text-emerald-400">{{ $totalHadir }}</span>
                        <span class="text-xs text-slate-400">/ {{ $totalAnggota }} Siswa</span>
                        <span class="ml-auto text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $persentaseHadir }}%</span>
                    </div>
                </div>
            </div>

            {{-- Program Latihan Note --}}
            @if ($sesi->program_latihan)
                <div class="mt-4 rounded-xl border border-emerald-100 bg-emerald-50/50 p-3.5 dark:border-emerald-950 dark:bg-emerald-950/20">
                    <div class="flex items-start gap-2.5">
                        <x-filament::icon icon="heroicon-m-academic-cap" class="h-4 w-4 text-emerald-600 mt-0.5 shrink-0 dark:text-emerald-400" />
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 dark:text-emerald-400">Materi / Program Latihan:</span>
                            <p class="mt-0.5 text-xs sm:text-sm text-slate-700 dark:text-slate-300 font-medium leading-relaxed">
                                "{{ $sesi->program_latihan }}"
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Progress Bar --}}
            <div class="mt-4">
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-500 ease-out"
                        style="width: {{ $persentaseHadir }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Summary Stat Badges --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="flex items-center gap-3 rounded-xl border border-slate-200/80 bg-white p-3.5 shadow-2xs dark:border-slate-800 dark:bg-slate-900">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                <x-filament::icon icon="heroicon-m-user-group" class="h-5 w-5" />
            </div>
            <div>
                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Anggota</div>
                <div class="text-lg font-bold text-slate-900 dark:text-white">{{ $totalAnggota }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3 rounded-xl border border-emerald-200/80 bg-emerald-50/40 p-3.5 shadow-2xs dark:border-emerald-900/50 dark:bg-emerald-950/20">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500 text-white shadow-xs shadow-emerald-500/20">
                <x-filament::icon icon="heroicon-m-check-badge" class="h-5 w-5" />
            </div>
            <div>
                <div class="text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Hadir</div>
                <div class="text-lg font-bold text-emerald-800 dark:text-emerald-300">{{ $totalHadir }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3 rounded-xl border border-rose-200/80 bg-rose-50/40 p-3.5 shadow-2xs dark:border-rose-900/50 dark:bg-rose-950/20">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-500 text-white shadow-xs shadow-rose-500/20">
                <x-filament::icon icon="heroicon-m-x-circle" class="h-5 w-5" />
            </div>
            <div>
                <div class="text-[11px] font-semibold text-rose-700 dark:text-rose-400 uppercase tracking-wider">Tidak Hadir</div>
                <div class="text-lg font-bold text-rose-800 dark:text-rose-300">{{ $totalTidakHadir }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3 rounded-xl border border-teal-200/80 bg-teal-50/40 p-3.5 shadow-2xs dark:border-teal-900/50 dark:bg-teal-950/20">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-teal-500 text-white shadow-xs shadow-teal-500/20">
                <x-filament::icon icon="heroicon-m-arrow-trending-up" class="h-5 w-5" />
            </div>
            <div>
                <div class="text-[11px] font-semibold text-teal-700 dark:text-teal-400 uppercase tracking-wider">Persentase</div>
                <div class="text-lg font-bold text-teal-800 dark:text-teal-300">{{ $persentaseHadir }}%</div>
            </div>
        </div>
    </div>

    {{-- Attendance Control Toolbar --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            {{-- Search Input --}}
            <div class="relative flex-1 min-w-[240px]">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <x-filament::icon icon="heroicon-m-magnifying-glass" class="h-4 w-4" />
                </div>
                <input type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama siswa, NISN, atau kelas..."
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 py-2.5 pl-10 pr-10 text-xs sm:text-sm text-slate-900 transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500" />

                @if (!empty($search))
                    <button type="button"
                        wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <x-filament::icon icon="heroicon-m-x-mark" class="h-4 w-4" />
                    </button>
                @endif
            </div>

            {{-- Mass Action Quick Buttons --}}
            <div class="flex items-center gap-2">
                <button type="button"
                    wire:click="markAllHadir"
                    class="inline-flex flex-1 sm:flex-none items-center justify-center gap-1.5 rounded-xl border border-emerald-300 bg-emerald-50 px-3.5 py-2 text-xs font-semibold text-emerald-700 shadow-2xs hover:bg-emerald-100 hover:border-emerald-400 transition active:scale-95 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 dark:hover:bg-emerald-900/80">
                    <x-filament::icon icon="heroicon-m-check-badge" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                    <span>Semua Hadir</span>
                </button>

                <button type="button"
                    wire:click="markAllTidakHadir"
                    class="inline-flex flex-1 sm:flex-none items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-2xs hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200 transition active:scale-95 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-rose-950/50 dark:hover:text-rose-300 dark:hover:border-rose-800">
                    <x-filament::icon icon="heroicon-m-x-circle" class="h-4 w-4 text-slate-400 hover:text-rose-500" />
                    <span>Semua Tidak Hadir</span>
                </button>
            </div>
        </div>

        @if (!empty($search))
            <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                Menampilkan hasil pencarian untuk <span class="font-semibold text-slate-800 dark:text-slate-200">"{{ $search }}"</span>: <span class="font-semibold text-emerald-600">{{ count($filteredAnggota) }}</span> siswa ditemukan.
            </div>
        @endif
    </div>

    {{-- Daftar Anggota Siswa --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden dark:border-slate-800 dark:bg-slate-900">
        <div class="border-b border-slate-100 bg-slate-50/75 px-5 py-3.5 dark:border-slate-800 dark:bg-slate-800/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300">
                    <x-filament::icon icon="heroicon-m-user-group" class="h-4 w-4 text-emerald-600" />
                    Daftar Kehadiran Anggota ({{ count($filteredAnggota) }})
                </div>
                <div class="text-[11px] text-slate-400">
                    Klik tombol status untuk mengubah kehadiran
                </div>
            </div>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($filteredAnggota as $index => $item)
                @php
                    $isHadir = ($item['status'] === 'hadir');
                @endphp
                <div class="group flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 transition hover:bg-slate-50/80 dark:hover:bg-slate-800/50 {{ $isHadir ? 'bg-emerald-50/15' : '' }}">
                    {{-- Student Information --}}
                    <div class="flex items-center gap-3.5 min-w-0">
                        {{-- Avatar Initial --}}
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xs font-bold text-white shadow-xs {{ $isHadir ? 'bg-emerald-600' : 'bg-slate-400 dark:bg-slate-700' }}">
                            {{ strtoupper(substr($item['nama'], 0, 2)) }}
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-sm text-slate-900 group-hover:text-emerald-700 transition dark:text-white dark:group-hover:text-emerald-400">
                                    {{ $item['nama'] }}
                                </span>

                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $item['kelas'] }}
                                </span>

                                @if (!empty($item['nisn']))
                                    <span class="text-[11px] text-slate-400 font-mono">
                                        NISN: {{ $item['nisn'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-0.5 flex items-center gap-2 text-xs text-slate-500">
                                <span>Gender: {{ $item['jenis_kelamin'] === 'P' ? 'Perempuan' : 'Laki-laki' }}</span>
                                @if ($item['rfid'])
                                    <span class="inline-flex items-center gap-1 rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                        <x-filament::icon icon="heroicon-m-check-badge" class="h-3 w-3" />
                                        RFID {{ $item['waktu_scan'] ? '('.$item['waktu_scan'].')' : '' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Status Control --}}
                    <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                        @if ($item['rfid'])
                            <div class="flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-1.5 text-xs font-semibold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300">
                                <x-filament::icon icon="heroicon-m-check-circle" class="h-4 w-4 text-emerald-600" />
                                <span>Hadir via RFID</span>
                            </div>
                        @else
                            {{-- Interactive Segmented Buttons --}}
                            <div class="inline-flex rounded-xl bg-slate-100 p-1 shadow-inner dark:bg-slate-800">
                                <button type="button"
                                    wire:click="setStatus({{ $index }}, 'hadir')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition active:scale-95 {{ $isHadir ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700 dark:text-slate-300 dark:hover:text-emerald-400' }}">
                                    <x-filament::icon icon="heroicon-m-check" class="h-3.5 w-3.5" />
                                    <span>Hadir</span>
                                </button>

                                <button type="button"
                                    wire:click="setStatus({{ $index }}, 'tidak_hadir')"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition active:scale-95 {{ !$isHadir ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-rose-700 dark:text-slate-300 dark:hover:text-rose-400' }}">
                                    <x-filament::icon icon="heroicon-m-x-mark" class="h-3.5 w-3.5" />
                                    <span>Tidak Hadir</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400 dark:text-slate-500">
                    <x-filament::icon icon="heroicon-o-user-group" class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600" />
                    <p class="mt-3 text-sm font-medium">Tidak ada anggota ekstrakurikuler yang sesuai.</p>
                    @if (!empty($search))
                        <button type="button"
                            wire:click="$set('search', '')"
                            class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:underline">
                            Hapus kata kunci pencarian
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    {{-- Bottom Sticky Save Bar --}}
    <div class="fixed bottom-0 left-0 lg:left-[18rem] right-0 z-30 border-t border-slate-200/80 bg-white/95 px-4 py-3.5 shadow-xl backdrop-blur-xl sm:px-8 dark:border-slate-800 dark:bg-slate-900/95">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4">
            {{-- Status Option Toggle --}}
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <input type="checkbox"
                    wire:model="tandaiSelesai"
                    class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 dark:border-slate-700 dark:bg-slate-800 dark:checked:bg-emerald-600" />
                <div>
                    <span class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">Tandai Sesi Ini Telah Selesai</span>
                    <p class="text-[11px] text-slate-400">Status sesi kegiatan akan otomatis diubah menjadi 'Selesai'</p>
                </div>
            </label>

            {{-- Submit Action --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:block text-right">
                    <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <span class="text-emerald-600 font-bold dark:text-emerald-400">{{ $totalHadir }}</span> dari {{ $totalAnggota }} Hadir
                    </div>
                    <div class="text-[10px] text-slate-400">{{ $persentaseHadir }}% Presensi Lengkap</div>
                </div>

                <button type="button"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 active:scale-95 disabled:opacity-50">
                    <span wire:loading.remove wire:target="submit">
                        <x-filament::icon icon="heroicon-m-check-circle" class="h-5 w-5" />
                    </span>
                    <span wire:loading wire:target="submit" class="animate-spin">
                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </span>
                    <span>Simpan Presensi Ekstra</span>
                </button>
            </div>
        </div>
    </div>

</div>
