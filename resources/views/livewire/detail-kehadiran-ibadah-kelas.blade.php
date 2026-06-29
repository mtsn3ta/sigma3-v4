<div class="overflow-x-auto">

    <table class="w-full text-sm text-left border rounded-lg overflow-hidden dark:border-gray-700">

        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-4 py-3 border-b dark:border-gray-700">
                    Nama Siswa
                </th>

                <th class="px-4 py-3 border-b dark:border-gray-700 text-center">
                    Dhuha
                </th>

                <th class="px-4 py-3 border-b dark:border-gray-700 text-center">
                    Dzuhur
                </th>

                <th class="px-4 py-3 border-b dark:border-gray-700">
                    Sumber
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">

            @foreach ($siswa as $s)
                @php

                    $dhuha = $s->kehadiranIbadah->firstWhere('jenis', 'dhuha');

                    $dzuhur = $s->kehadiranIbadah->firstWhere('jenis', 'dzuhur');

                @endphp

                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">

                    <td class="px-4 py-3">
                        {{ $s->nama }}
                    </td>

                    <td class="px-4 py-3 text-center">

                        <x-filament::badge :color="match ($dhuha?->status) {
                            'hadir' => 'success',
                            'menstruasi' => 'warning',
                            'tidak_hadir' => 'danger',
                            default => 'gray',
                        }">
                            {{ match ($dhuha?->status) {
                                'hadir' => '✓',
                                'menstruasi' => 'M',
                                'tidak_hadir' => '✕',
                                default => '-',
                            } }}
                        </x-filament::badge>

                    </td>

                    <td class="px-4 py-3 text-center">

                        <x-filament::badge :color="match ($dzuhur?->status) {
                            'hadir' => 'success',
                            'menstruasi' => 'warning',
                            'tidak_hadir' => 'danger',
                            default => 'gray',
                        }">
                            {{ match ($dzuhur?->status) {
                                'hadir' => '✓',
                                'menstruasi' => 'M',
                                'tidak_hadir' => '✕',
                                default => '-',
                            } }}
                        </x-filament::badge>

                    </td>

                    <td class="px-4 py-3">

                        @if ($dhuha?->sumber || $dzuhur?->sumber)
                            {{ strtoupper($dhuha?->sumber ?? $dzuhur?->sumber) }}
                        @else
                            -
                        @endif

                    </td>

                </tr>
            @endforeach

        </tbody>

    </table>

    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">

        <span class="mr-4">
            <strong>✓</strong> = Hadir
        </span>

        <span class="mr-4">
            <strong>M</strong> = Menstruasi
        </span>

        <span>
            <strong>✕</strong> = Tidak Hadir
        </span>

    </div>

</div>
