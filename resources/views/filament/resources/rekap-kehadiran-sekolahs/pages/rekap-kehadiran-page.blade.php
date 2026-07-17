<x-filament-panels::page>
    <div x-data="{ activeTab: 'harian' }" style="display: flex; flex-direction: column; gap: 2rem;">

        <x-filament::tabs label="Rekap Tabs" class="w-full" style="margin-bottom: 1rem;">

            <x-filament::tabs.item x-on:click="activeTab = 'harian'" alpine-active="activeTab === 'harian'"
                icon="heroicon-m-calendar-days">
                Harian
            </x-filament::tabs.item>

            <x-filament::tabs.item x-on:click="activeTab = 'bulanan'" alpine-active="activeTab === 'bulanan'"
                icon="heroicon-m-calendar">
                Bulanan
            </x-filament::tabs.item>

            <x-filament::tabs.item x-on:click="activeTab = 'semester'" alpine-active="activeTab === 'semester'"
                icon="heroicon-m-academic-cap">
                Semester
            </x-filament::tabs.item>

        </x-filament::tabs>

        <div class="relative w-full" style="width: 100%;">
            <div x-show="activeTab === 'harian'" x-cloak x-transition.opacity.duration.300ms>
                <livewire:rekap-harian-table />
            </div>

            <div x-show="activeTab === 'bulanan'" x-cloak x-transition.opacity.duration.300ms>
                <livewire:rekap-bulanan-table />
            </div>

            <div x-show="activeTab === 'semester'" x-cloak x-transition.opacity.duration.300ms>
                <livewire:rekap-semester-table />
            </div>
        </div>

    </div>
</x-filament-panels::page>
