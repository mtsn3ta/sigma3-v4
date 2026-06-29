<x-filament-panels::page>
    <div x-data="{ activeTab: 'harian' }">

        <x-filament::tabs label="Rekap Tabs" class="mb-6">
            <x-filament::tabs.item x-on:click="activeTab = 'harian'"
                alpine-active="activeTab === 'harian'">Harian</x-filament::tabs.item>
            <x-filament::tabs.item x-on:click="activeTab = 'bulanan'"
                alpine-active="activeTab === 'bulanan'">Bulanan</x-filament::tabs.item>
            <x-filament::tabs.item x-on:click="activeTab = 'semester'"
                alpine-active="activeTab === 'semester'">Semester</x-filament::tabs.item>
        </x-filament::tabs>

        <div x-show="activeTab === 'harian'" x-cloak><livewire:rekap-harian-ibadah-table /></div>
        <div x-show="activeTab === 'bulanan'" x-cloak><livewire:rekap-bulanan-ibadah-table /></div>
        <div x-show="activeTab === 'semester'" x-cloak><livewire:rekap-semester-ibadah-table /></div>

    </div>
</x-filament-panels::page>
