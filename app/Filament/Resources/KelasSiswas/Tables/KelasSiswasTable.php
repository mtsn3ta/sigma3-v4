<?php

namespace App\Filament\Resources\KelasSiswas\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Support\KelasHelper;
use App\Services\StudentPlacement\StudentPlacementService;
use App\Services\ActivityLogService;
use App\Models\Kelas;
use Filament\Forms\Components\Placeholder;

class KelasSiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('siswa.jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => '-',
                    })
                    ->color(fn(string $state) => match ($state) {
                        'L' => 'info',
                        'P' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->sortable(),

                TextColumn::make('siswa.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->color(fn(string $state) => match ($state) {
                        'aktif' => 'success',
                        'mutasi' => 'danger',
                        'lulus' => 'info',
                        default => 'gray',
                    }),
            ])

            ->filters([

                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship(
                        'tahunAjaran',
                        'tahun'
                    )
                    ->searchable()
                    ->preload()
                    ->default(
                        TahunAjaran::query()
                            ->where('aktif', true)
                            ->value('id')
                    ),
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship(
                        'kelas',
                        'nama_kelas'
                    )
                    ->searchable()
                    ->preload(),
                SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->relationship(
                        'siswa',
                        'jenis_kelamin'
                    )
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                SelectFilter::make('status')
                    ->label('Status Siswa')
                    ->relationship('siswa', 'status')
                    ->options([
                        'aktif' => 'Aktif',
                        'mutasi' => 'Mutasi',
                        'lulus' => 'Lulus',
                    ])
                    ->default('aktif'),

            ])

            ->recordActions([

                Action::make('ubahKelas')

                    ->label('Ubah Kelas')

                    ->icon('heroicon-o-arrows-right-left')

                    ->color('warning')

                    ->schema([

                        Placeholder::make('siswa')
                            ->label('Siswa')
                            ->content(
                                fn($record) => $record->siswa->nama
                            ),

                        Placeholder::make('tahun')
                            ->label('Tahun Ajaran')
                            ->content(
                                fn($record) => $record->tahunAjaran->tahun
                            ),

                        Placeholder::make('kelas')
                            ->label('Kelas Saat Ini')
                            ->content(
                                fn($record) => $record->kelas->nama_kelas
                            ),

                        Select::make('kelas_id')

                            ->label('Kelas Baru')

                            ->options(
                                fn($record) =>
                                KelasHelper::optionsExcept(
                                    $record->kelas_id
                                )
                            )

                            ->searchable()

                            ->preload()

                            ->required(),

                    ])

                    ->action(function ($record, array $data) {

                        $kelasLama = $record->kelas->nama_kelas;

                        $kelasBaru = Kelas::findOrFail($data['kelas_id']);

                        $result = app(StudentPlacementService::class)
                            ->move(
                                placement: $record,
                                kelasId: $data['kelas_id'],
                            );

                        if (! $result['changed']) {

                            Notification::make()
                                ->title('Tidak Ada Perubahan')
                                ->body('Kelas yang dipilih sama dengan kelas saat ini.')
                                ->warning()
                                ->send();

                            return;
                        }

                        ActivityLogService::log(
                            'Perubahan Kelas',
                            sprintf(
                                '%s | %s → %s',
                                $record->siswa->nama,
                                $kelasLama,
                                $kelasBaru->nama_kelas,
                            )
                        );

                        Notification::make()
                            ->title('Perubahan Kelas Berhasil')
                            ->body(
                                sprintf(
                                    '%s berhasil dipindahkan dari %s ke %s.',
                                    $record->siswa->nama,
                                    $kelasLama,
                                    $kelasBaru->nama_kelas,
                                )
                            )
                            ->success()
                            ->send();
                    })

            ])

            ->toolbarActions([]);
    }
}
