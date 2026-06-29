<div>
    {{-- Membungkus form di dalam Modal bawaan Filament --}}
    <x-filament::modal id="jurnal-modal" width="5xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            {{ $record ? 'Edit Jurnal Mengajar' : 'Isi Jurnal Mengajar' }}
        </x-slot>

        <form wire:submit="submit" class="space-y-6">

            {{ $this->form }}

            @if ($siswaTidakHadir->isNotEmpty())
                <x-filament::section heading="Siswa Tidak Hadir Sekolah" class="mt-6">
                    <div class="space-y-2">
                        @foreach ($siswaTidakHadir as $item)
                            <div class="flex items-center justify-between rounded-lg border p-3 dark:border-gray-700">
                                <div class="font-medium">
                                    {{ $item->siswa->nama }}
                                </div>
                                <x-filament::badge :color="match ($item->status) {
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
                </x-filament::section>
            @endif

            {{-- Tombol Aksi di bagian bawah modal --}}
            <div class="mt-6 flex justify-end gap-x-3">
                <x-filament::button color="gray" x-on:click="close">
                    Batal
                </x-filament::button>

                <x-filament::button type="submit">
                    {{ $record ? 'Simpan Perubahan' : 'Simpan Jurnal' }}
                </x-filament::button>
            </div>

        </form>
    </x-filament::modal>

    <x-filament-actions::modals />
</div>
