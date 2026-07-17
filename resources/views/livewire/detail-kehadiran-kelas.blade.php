<div
    style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
    <table style="width: 100%; font-size: 0.875rem; text-align: left; border-collapse: collapse;">
        <thead style="background-color: rgba(249, 250, 251, 0.7); border-bottom: 1px solid #e5e7eb;">
            <tr>
                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                    Nama Siswa
                </th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center; color: #111827;">
                    Status
                </th>
                <th style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                    Keterangan
                </th>
            </tr>
        </thead>
        <tbody style="color: #374151;">
            @forelse ($siswa as $s)
                @php
                    $kehadiran = $s->kehadiranSekolah->first();
                    $status = $kehadiran ? strtolower($kehadiran->status) : 'belum ada data';
                    $keterangan = $kehadiran && $kehadiran->keterangan ? $kehadiran->keterangan : null;
                @endphp

                <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#f9fafb'"
                    onmouseout="this.style.backgroundColor='transparent'">

                    <td style="padding: 0.75rem 1rem; font-weight: 500; color: #111827;">
                        {{ $s->nama }}
                    </td>

                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <x-filament::badge :color="match ($status) {
                            'hadir' => 'success',
                            'terlambat' => 'warning',
                            'izin' => 'info',
                            'sakit' => 'gray',
                            'alfa' => 'danger',
                            default => 'gray',
                        }">
                            {{ ucfirst($status) }}
                        </x-filament::badge>
                    </td>

                    <td style="padding: 0.75rem 1rem; color: #4b5563;">
                        @if ($keterangan)
                            {{ $keterangan }}
                        @else
                            <span style="font-style: italic; color: #9ca3af;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="padding: 2rem 1rem; text-align: center; color: #6b7280;">
                        Belum ada data siswa di kelas ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
