<div>
    <x-filament::modal id="guru-berhalangan-modal" width="5xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            {{ $record ? 'Edit Guru Berhalangan' : 'Ajukan Guru Berhalangan' }}
        </x-slot>

        <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Container Form agar tetap rapi --}}
            <div style="width: 100%;">
                {{ $this->form }}
            </div>

            {{-- Footer Modal dengan Border Pemisah --}}
            <div
                style="
                display: flex;
                justify-content: flex-end;
                gap: 0.75rem;
                padding-top: 1.25rem;
                margin-top: 0.5rem;
                border-top: 1px solid #f3f4f6;
            ">
                <x-filament::button color="gray" x-on:click="close">
                    Batal
                </x-filament::button>

                <x-filament::button type="submit">
                    {{ $record ? 'Simpan Perubahan' : 'Ajukan Tugas' }}
                </x-filament::button>
            </div>

        </form>
    </x-filament::modal>

    <x-filament-actions::modals />
</div>
