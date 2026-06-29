<div>
    <form wire:submit="submit">
        {{ $this->form }}

        <x-filament::button type="submit" icon="heroicon-o-check-circle" class="w-full">
            Simpan Verifikasi
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
