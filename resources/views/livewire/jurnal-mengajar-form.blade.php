<div>
    <x-filament::modal id="jurnal-modal" width="4xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            <div class="flex items-center gap-2.5" style="display: flex; align-items: center; gap: 0.625rem;">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800/40" style="width: 36px; height: 36px; min-width: 36px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <x-heroicon-o-clipboard-document-check class="w-5 h-5" style="width: 20px; height: 20px;" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" style="margin: 0; font-size: 1.125rem; font-weight: 700; line-height: 1.3;">
                        {{ $record ? 'Edit Jurnal Pembelajaran' : 'Isi Jurnal Pembelajaran & Presensi' }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-normal" style="margin: 0.125rem 0 0 0; font-size: 0.75rem; color: #6b7280;">
                        Catat aktivitas materi KBM dan kehadiran siswa di kelas hari ini.
                    </p>
                </div>
            </div>
        </x-slot>

        {{-- BANNER TERKUNCI: Agenda / Libur --}}
        @if ($isLocked)
            <div class="flex items-start gap-3.5 p-4 rounded-xl border border-rose-300 bg-rose-50/90 text-rose-900 dark:bg-rose-950/30 dark:border-rose-900/50 dark:text-rose-200 mb-4">
                <x-heroicon-o-lock-closed class="w-6 h-6 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5" />
                <div>
                    <h4 class="font-bold text-sm text-rose-950 dark:text-rose-100">
                        Hari Ini Tidak Perlu Mengisi Jurnal
                    </h4>
                    <p class="text-xs text-rose-800 dark:text-rose-300 mt-0.5 leading-relaxed">
                        {{ $lockMessage }}
                    </p>
                </div>
            </div>
        @endif

        <form wire:submit="submit" class="flex flex-col gap-5">
            <div class="w-full">
                {{ $this->form }}
            </div>

            {{-- List Siswa Tidak Hadir Sekolah --}}
            @if ($siswaTidakHadir->isNotEmpty() && !$isLocked)
                <div class="rounded-xl border border-amber-200/90 bg-gradient-to-r from-amber-50/70 to-orange-50/40 p-4 dark:border-amber-900/40 dark:from-amber-950/20 dark:to-orange-950/10">
                    <h4 class="text-xs font-bold text-amber-900 dark:text-amber-200 mb-2.5 flex items-center gap-2 uppercase tracking-wider">
                        <x-heroicon-o-information-circle class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                        Siswa Sudah Berhalangan Hadir di Sekolah Hari Ini
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($siswaTidakHadir as $item)
                            <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-white/90 dark:bg-gray-800/80 border border-amber-100 dark:border-gray-700/60 shadow-xs">
                                <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate pr-2">
                                    {{ $item->siswa->nama }}
                                </span>
                                <x-filament::badge size="sm" :color="match ($item->status) {
                                    'sakit' => 'warning',
                                    'izin' => 'info',
                                    'alfa' => 'danger',
                                    default => 'gray',
                                }">
                                    {{ ucfirst($item->status) }}
                                </x-filament::badge>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-amber-700/80 dark:text-amber-400/80 mt-2">
                        * Siswa di atas otomatis dikecualikan dari pilihan siswa tidak ikut pada Langkah 4.
                    </p>
                </div>
            @endif

            {{-- Footer Modal --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <x-filament::button color="gray" size="md" x-on:click="close">
                    Batal
                </x-filament::button>

                @if (!$isLocked)
                    <x-filament::button type="submit" size="md" icon="heroicon-o-check-circle"
                        wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">
                            {{ $record ? 'Simpan Perubahan' : 'Simpan Jurnal' }}
                        </span>
                        <span wire:loading wire:target="submit">
                            Menyimpan...
                        </span>
                    </x-filament::button>
                @endif
            </div>
        </form>
    </x-filament::modal>

    <x-filament-actions::modals />
</div>
