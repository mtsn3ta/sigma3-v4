<?php

namespace App\Filament\Resources\MonitoringKehadirans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Kelas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class MonitoringKehadiransTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->modifyQueryUsing(function (Builder $query) {

                return $query

                    ->where('siswa.status', 'aktif')

                    ->leftJoin('kelas_siswa', function ($join) {

                        $join->on('kelas_siswa.siswa_id', '=', 'siswa.id')
                            ->where('kelas_siswa.status', 'aktif');
                    })

                    ->leftJoin('kelas', 'kelas.id', '=', 'kelas_siswa.kelas_id')

                    ->leftJoin('kehadiran_sekolah', function ($join) {

                        $join->on('kehadiran_sekolah.siswa_id', '=', 'siswa.id')
                            ->whereDate(
                                'kehadiran_sekolah.tanggal',
                                today()
                            );
                    })

                    ->select([
                        'siswa.*',

                        'kelas.nama_kelas',

                        'kehadiran_sekolah.status as status_kehadiran',

                        'kehadiran_sekolah.sumber',

                        'kehadiran_sekolah.jam_datang',

                        'kehadiran_sekolah.jam_pulang',

                        'kehadiran_sekolah.keterangan',

                        'kehadiran_sekolah.petugas_id',
                    ]);
            })

            ->columns([

                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('jam_datang')
                    ->label('Datang')
                    ->time('H:i')
                    ->placeholder('-')
                    ->alignCenter(),
                TextColumn::make('jam_pulang')
                    ->label('Pulang')
                    ->time('H:i')
                    ->placeholder('-')
                    ->alignCenter(),
                TextColumn::make('status_kehadiran')
                    ->label('Status')
                    ->badge()
                    ->color(fn(?string $state) => match ($state) {
                        'hadir' => 'success',
                        'terlambat' => 'warning',
                        'izin' => 'info',
                        'sakit' => 'primary',
                        'alfa' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(?string $state) => match ($state) {
                        'hadir' => 'Hadir',
                        'terlambat' => 'Terlambat',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alfa' => 'Alfa',
                        default => 'Belum',
                    }),

            ])

            ->filters([

                Filter::make('kelas')
                    ->form([
                        Select::make('kelas')
                            ->options(
                                Kelas::query()
                                    ->orderBy('tingkat')
                                    ->orderBy('nama_kelas')
                                    ->pluck('nama_kelas', 'id')
                            )
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        if (blank($data['kelas'])) {
                            return $query;
                        }

                        return $query->where(
                            'kelas.id',
                            $data['kelas']
                        );
                    }),

            ])

            ->recordActions([])

            ->toolbarActions([]);
    }
}
