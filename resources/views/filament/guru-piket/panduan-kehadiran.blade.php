<div style="display: flex; flex-direction: column; gap: 1rem; font-size: 0.875rem; color: #374151;">

    {{-- Keterangan Status Kehadiran --}}
    @php
        $statuses = [
            [
                'icon' => '🟢',
                'label' => 'Hadir',
                'color' => '#059669',
                'desc' => 'Siswa hadir tetapi tidak melakukan scan RFID.',
            ],
            [
                'icon' => '🟡',
                'label' => 'Terlambat',
                'color' => '#d97706',
                'desc' => 'Siswa hadir tetapi datang melewati batas waktu.',
            ],
            [
                'icon' => '🔵',
                'label' => 'Izin',
                'color' => '#2563eb',
                'desc' => 'Ada izin resmi dari orang tua atau wali.',
            ],
            ['icon' => '🟣', 'label' => 'Sakit', 'color' => '#7c3aed', 'desc' => 'Siswa sakit.'],
            ['icon' => '🔴', 'label' => 'Alfa', 'color' => '#dc2626', 'desc' => 'Tidak hadir tanpa keterangan.'],
        ];
    @endphp

    @foreach ($statuses as $status)
        <div style="display: flex; flex-direction: column; gap: 0.25rem;">
            <div
                style="font-weight: 600; color: {{ $status['color'] }}; display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-size: 1.1rem;">{{ $status['icon'] }}</span> {{ $status['label'] }}
            </div>
            <p style="margin: 0; color: #4b5563; padding-left: 1.6rem; font-size: 0.85rem; line-height: 1.4;">
                {{ $status['desc'] }}
            </p>
        </div>
    @endforeach

</div>
