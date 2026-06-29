<div>

    <x-filament::modal id="guru-berhalangan-modal" width="5xl" :close-by-clicking-away="false">

        <x-slot name="heading">
            {{ $record ? 'Edit Guru Berhalangan' : 'Ajukan Guru Berhalangan' }}
        </x-slot>

        <form wire:submit="submit" class="space-y-6">

            {{ $this->form }}

            <div class="mt-6 flex justify-end gap-x-3">

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
