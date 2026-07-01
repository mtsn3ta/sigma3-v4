<div
    style="width: 100%; padding: 1.5rem; background: #fff; border-radius: 1rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

    <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Form Builder Filament --}}
        <div style="width: 100%;">
            {{ $this->form }}
        </div>

        {{-- Tombol Aksi --}}
        <div style="padding-top: 0.5rem;">
            <x-filament::button type="submit" icon="heroicon-o-check-circle"
                style="width: 100%; height: 3rem; font-size: 1rem; font-weight: 600;">
                Simpan Verifikasi
            </x-filament::button>
        </div>

    </form>

    <x-filament-actions::modals />
</div>
