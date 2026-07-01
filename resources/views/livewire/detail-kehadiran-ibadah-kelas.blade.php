<div style="width: 100%;">

    <div
        style="overflow-x: auto; border-radius: 0.75rem; border: 1px solid #e5e7eb; background-color: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">

        <table style="width: 100%; font-size: 0.875rem; text-align: left; border-collapse: collapse;">

            <thead style="background-color: rgba(249, 250, 251, 0.7); border-bottom: 1px solid #e5e7eb;">
                <tr>
                    <th style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                        Nama Siswa
                    </th>
                    <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center; color: #111827;">
                        Dhuha
                    </th>
                    <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center; color: #111827;">
                        Dzuhur
                    </th>
                    <th style="padding: 0.75rem 1rem; font-weight: 600; text-align: center; color: #111827;">
                        Sumber
                    </th>
                </tr>
            </thead>

            <tbody style="color: #374151;">
                @forelse ($siswa as $s)
                    @php
                        $dhuha = $s->kehadiranIbadah->firstWhere('jenis', 'dhuha');
                        $dzuhur = $s->kehadiranIbadah->firstWhere('jenis', 'dzuhur');
                    @endphp

                    <tr style="border-bottom: 1px solid #f3f4f6; transition: background-color 0.2s;"
                        onmouseover="this.style.backgroundColor='#f9fafb'"
                        onmouseout="this.style.backgroundColor='transparent'">

                        <td style="padding: 0.75rem 1rem; font-weight: 500; color: #111827;">
                            {{ $s->nama }}
                        </td>

                        <td style="padding: 0.75rem 1rem; text-align: center;">
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

                        <td style="padding: 0.75rem 1rem; text-align: center;">
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

                        <td style="padding: 0.75rem 1rem; text-align: center; color: #6b7280; font-size: 0.75rem;">
                            @if ($dhuha?->sumber || $dzuhur?->sumber)
                                <span
                                    style="background-color: #f3f4f6; padding: 0.125rem 0.375rem; border-radius: 0.25rem; border: 1px solid #e5e7eb;">
                                    {{ strtoupper($dhuha?->sumber ?? $dzuhur?->sumber) }}
                                </span>
                            @else
                                <span style="font-style: italic; color: #9ca3af;">-</span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 2rem 1rem; text-align: center; color: #6b7280;">
                            Belum ada data kehadiran ibadah.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <div
        style="margin-top: 1rem; font-size: 0.75rem; color: #6b7280; display: flex; gap: 1.5rem; justify-content: center; align-items: center; padding: 0.75rem; background-color: rgba(249, 250, 251, 0.5); border-radius: 0.5rem; border: 1px solid #f3f4f6;">
        <span style="display: flex; align-items: center; gap: 0.25rem;">
            <span style="color: #059669; font-weight: bold;">✓</span> Hadir
        </span>
        <span style="display: flex; align-items: center; gap: 0.25rem;">
            <span style="color: #d97706; font-weight: bold;">M</span> Menstruasi
        </span>
        <span style="display: flex; align-items: center; gap: 0.25rem;">
            <span style="color: #dc2626; font-weight: bold;">✕</span> Tidak Hadir
        </span>
    </div>

</div>
