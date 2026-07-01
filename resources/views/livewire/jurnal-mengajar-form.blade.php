<div>
    {{-- Membungkus form di dalam Modal bawaan Filament --}}
    <x-filament::modal id="jurnal-modal" width="5xl" :close-by-clicking-away="false">
        <x-slot name="heading">
            {{ $record ? 'Edit Jurnal Mengajar' : 'Isi Jurnal Mengajar' }}
        </x-slot>

        <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Form Builder --}}
            <div style="width: 100%;">
                {{ $this->form }}
            </div>

            {{-- List Siswa Tidak Hadir (Modern Style) --}}
            @if ($siswaTidakHadir->isNotEmpty())
                <div style="padding: 1rem; border-radius: 0.75rem; border: 1px solid #e5e7eb; background: #f9fafb;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 1rem;">
                        Siswa Tidak Hadir Sekolah
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach ($siswaTidakHadir as $item)
                            <div
                                style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border-radius: 0.5rem; background: #fff; border: 1px solid #f3f4f6;">
                                <span style="font-size: 0.875rem; font-weight: 500; color: #111827;">
                                    {{ $item->siswa->nama }}
                                </span>
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
                </div>
            @endif

            {{-- Footer Modal --}}
            <div
                style="display: flex; justify-content: flex-end; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
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
