<div class="space-y-6">

    <x-filament::section>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

            <div>
                <div class="text-sm text-gray-500">
                    Ekstrakurikuler
                </div>

                <div class="font-semibold">
                    {{ $sesi->ekstrakurikuler->nama }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Hari / Tanggal
                </div>

                <div class="font-semibold">
                    {{ \Carbon\Carbon::parse($sesi->tanggal)->translatedFormat('l, d F Y') }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Jam
                </div>

                <div class="font-semibold">
                    {{ $sesi->jam_mulai }}
                    -
                    {{ $sesi->jam_selesai }}
                </div>
            </div>

            <div>
                <div class="text-sm text-gray-500">
                    Pelatih
                </div>

                <div class="font-semibold">
                    {{ $sesi->pelatih?->nama ?? '-' }}
                </div>
            </div>

        </div>

        <div class="mt-6">

            <div class="text-sm text-gray-500">
                Program Latihan
            </div>

            <div class="font-medium">
                {{ $sesi->program_latihan }}
            </div>

        </div>

    </x-filament::section>

    <x-filament::section>

        <x-slot name="heading">
            Daftar Anggota
        </x-slot>

        <div class="space-y-3">

            @foreach ($anggota as $index => $item)
                <div class="flex items-center justify-between rounded-lg border p-4">

                    <div>

                        <div class="font-medium">
                            {{ $item['nama'] }}
                        </div>

                    </div>

                    @if ($item['rfid'])
                        <x-filament::badge color="success">
                            RFID ✓
                        </x-filament::badge>
                    @else
                        <div class="flex gap-6">

                            <label class="flex items-center gap-2">

                                <input type="radio" wire:model="anggota.{{ $index }}.status" value="hadir">

                                Hadir

                            </label>

                            <label class="flex items-center gap-2">

                                <input type="radio" wire:model="anggota.{{ $index }}.status"
                                    value="tidak_hadir">

                                Tidak Hadir

                            </label>

                        </div>
                    @endif

                </div>
            @endforeach

        </div>

    </x-filament::section>

    <div class="flex justify-end">

        <x-filament::button wire:click="submit" color="success">

            Simpan Absensi

        </x-filament::button>

    </div>

</div>
