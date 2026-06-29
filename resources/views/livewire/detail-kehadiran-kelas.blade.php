<div class="overflow-x-auto">
    <table class="w-full text-sm text-left border rounded-lg overflow-hidden dark:border-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-4 py-3 border-b dark:border-gray-700">Nama Siswa</th>
                <th class="px-4 py-3 border-b dark:border-gray-700 text-center">Status</th>
                <th class="px-4 py-3 border-b dark:border-gray-700">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
            @foreach ($siswa as $s)
                @php
                    $kehadiran = $s->kehadiranSekolah->first();
                    $status = $kehadiran ? strtolower($kehadiran->status) : 'belum ada data';
                    $keterangan = $kehadiran ? $kehadiran->keterangan : '-';
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-4 py-3">{{ $s->nama }}</td>
                    <td class="px-4 py-3 text-center">
                        <x-filament::badge :color="match ($status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'info',
                            'sakit' => 'info',
                            'alfa' => 'danger',
                            default => 'gray',
                        }">
                            {{ ucfirst($status) }}
                        </x-filament::badge>
                    </td>
                    <td class="px-4 py-3">{{ $keterangan ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
