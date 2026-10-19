<div>
    <x-filament::modal id="guru-berhalangan-modal" width="3xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            <div class="flex items-center gap-2.5">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50 text-amber-600 border border-amber-200/80 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/40">
                    <x-heroicon-o-document-plus class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ $record ? 'Edit Tugas Inval' : 'Buat Tugas Guru Izin / Inval' }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-normal">
                        Titipkan tugas pembelajaran kepada guru piket saat berhalangan hadir.
                    </p>
                </div>
            </div>
        </x-slot>

        <form wire:submit="submit" class="flex flex-col gap-5">
            <div class="w-full">
                {{ $this->form }}
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <x-filament::button color="gray" size="md" x-on:click="close">
                    Batal
                </x-filament::button>

                <x-filament::button type="submit" size="md" color="warning" icon="heroicon-o-paper-airplane"
                    wire:loading.attr="disabled" wire:target="submit">
                    <span wire:loading.remove wire:target="submit">
                        {{ $record ? 'Simpan Perubahan' : 'Kirim Tugas Inval' }}
                    </span>
                    <span wire:loading wire:target="submit">
                        Mengirim...
                    </span>
                </x-filament::button>
            </div>
        </form>
    </x-filament::modal>

    <x-filament-actions::modals />
</div>
