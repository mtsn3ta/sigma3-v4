<div
    style="width:100%;padding:1.5rem;background:#fff;border-radius:1rem;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.1);">

    {{-- Tabs --}}
    {{-- Tab 1 : Pengajuan Orang Tua --}}
    {{-- Tab 2 : Verifikasi Manual --}}

    <x-filament::tabs>

        <x-filament::tabs.item active>
            Pengajuan Orang Tua
        </x-filament::tabs.item>

        <x-filament::tabs.item>
            Verifikasi Kehadiran
        </x-filament::tabs.item>

    </x-filament::tabs>

    {{-- isi tab nanti kita buat --}}

    <div class="mt-6">

        <form wire:submit="submit">

            {{ $this->form }}

            <div class="pt-4">

                <x-filament::button type="submit" icon="heroicon-o-check-circle" class="w-full">

                    Simpan Verifikasi

                </x-filament::button>

            </div>

        </form>

    </div>

    <x-filament-actions::modals />

</div>
