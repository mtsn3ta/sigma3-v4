<div>
    <x-filament::modal id="jurnal-modal" width="4xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            <span style="font-size: 1.35rem; font-weight: 700;">
                {{ $record ? 'Edit Jurnal Mengajar' : 'Isi Jurnal Mengajar' }}
            </span>
        </x-slot>

        {{-- BANNER TERKUNCI: diperbesar total, kontras tinggi --}}
        @if ($isLocked)
            <div
                style="background-color: #fef2f2; border: 2px solid #f87171; padding: 20px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 16px;">
                <x-heroicon-o-lock-closed style="width: 40px; height: 40px; color: #dc2626; flex-shrink: 0;" />
                <div>
                    <h4 style="color: #991b1b; font-weight: 700; margin: 0 0 4px 0; font-size: 1.15rem;">
                        Hari Ini Tidak Perlu Mengisi Jurnal
                    </h4>
                    <p style="color: #b91c1c; margin: 0; font-size: 1rem; line-height: 1.5;">
                        {{ $lockMessage }}
                    </p>
                </div>
            </div>
        @endif

        <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 1.5rem;">

            <div style="width: 100%; font-size: 1rem;">
                {{ $this->form }}
            </div>

            {{-- List Siswa Tidak Hadir Sekolah (bukan jurnal) --}}
            @if ($siswaTidakHadir->isNotEmpty() && !$isLocked)
                <div style="padding: 1.25rem; border-radius: 0.75rem; border: 2px solid #e5e7eb; background: #f9fafb;">
                    <h4
                        style="font-size: 1.05rem; font-weight: 700; color: #374151; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                        <x-heroicon-o-information-circle style="width: 22px; height: 22px; color: #6b7280;" />
                        Info: Siswa yang Sudah Tidak Masuk Sekolah Hari Ini
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                        @foreach ($siswaTidakHadir as $item)
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; padding: 0.9rem 1rem; border-radius: 0.6rem; background: #fff; border: 1px solid #f3f4f6;">
                                <span style="font-size: 1.05rem; font-weight: 600; color: #111827;">
                                    {{ $item->siswa->nama }}
                                </span>
                                <x-filament::badge size="lg" :color="match ($item->status) {
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
                    <p style="font-size: 0.9rem; color: #6b7280; margin-top: 0.75rem;">
                        Siswa di atas otomatis dikecualikan dari daftar pilihan pada "Langkah 4".
                    </p>
                </div>
            @endif

            {{-- Footer Modal: tombol besar & jelas --}}
            <div
                style="display: flex; justify-content: flex-end; gap: 0.85rem; padding-top: 1.25rem; border-top: 2px solid #f3f4f6;">
                <x-filament::button color="gray" size="lg" x-on:click="close">
                    Batal
                </x-filament::button>

                @if (!$isLocked)
                    <x-filament::button type="submit" size="lg" icon="heroicon-o-check-circle"
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
