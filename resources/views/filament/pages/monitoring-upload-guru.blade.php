<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.25rem;">

        <x-filament::section>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <x-filament::button
                    wire:click="setTab('rekap')"
                    :color="$tab === 'rekap' ? 'primary' : 'gray'"
                    icon="heroicon-m-clipboard-document-check"
                >
                    Rekap Guru
                </x-filament::button>

                <x-filament::button
                    wire:click="setTab('jurnal')"
                    :color="$tab === 'jurnal' ? 'primary' : 'gray'"
                    icon="heroicon-m-clipboard-document-list"
                >
                    Jurnal &amp; Dokumentasi KBM
                </x-filament::button>
            </div>
        </x-filament::section>

        @if ($tab === 'rekap')
            <livewire:monitoring-upload-guru-table />
        @else
            <livewire:monitoring-jurnal-guru-table />
        @endif

    </div>
</x-filament-panels::page>
