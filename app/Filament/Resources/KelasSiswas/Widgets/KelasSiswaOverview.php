<?php

namespace App\Filament\Resources\KelasSiswas\Widgets;

use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KelasSiswaOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $tahunAktif = TahunAjaran::query()
            ->where('aktif', true)
            ->first();

        if (! $tahunAktif) {

            return [

                Stat::make(
                    'Tahun Ajaran',
                    'Belum Ada'
                ),

            ];
        }

        $query = KelasSiswa::query()
            ->where(
                'tahun_ajaran_id',
                $tahunAktif->id,
            )
            ->whereHas('siswa', fn ($q) => $q->where('status', 'aktif'));

        return [

            Stat::make(
                'Tahun Aktif',
                $tahunAktif->tahun,
            )
                ->description('Tahun ajaran yang sedang berjalan')
                ->icon('heroicon-o-calendar-days'),

            Stat::make(
                'Total Siswa',
                $query->count(),
            )
                ->description('Siswa aktif pada tahun ajaran ini')
                ->icon('heroicon-o-users'),

            Stat::make(
                'Total Kelas',
                (clone $query)
                    ->distinct('kelas_id')
                    ->count('kelas_id'),
            )
                ->description('Jumlah kelas yang digunakan')
                ->icon('heroicon-o-building-office'),

            Stat::make(
                'Laki-laki',
                (clone $query)
                    ->whereHas(
                        'siswa',
                        fn($query) => $query->where(
                            'jenis_kelamin',
                            'L'
                        )
                    )
                    ->count(),
            )
                ->icon('heroicon-o-user'),

            Stat::make(
                'Perempuan',
                (clone $query)
                    ->whereHas(
                        'siswa',
                        fn($query) => $query->where(
                            'jenis_kelamin',
                            'P'
                        )
                    )
                    ->count(),
            )
                ->icon('heroicon-o-user'),

        ];
    }
}
