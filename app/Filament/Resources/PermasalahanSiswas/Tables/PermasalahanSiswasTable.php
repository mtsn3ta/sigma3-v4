<?php

namespace App\Filament\Resources\PermasalahanSiswas\Tables;

use App\Support\KelasHelper;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;

class PermasalahanSiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort('tanggal', 'desc')

            ->columns([

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas')
                    ->badge(),

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('permasalahan')
                    ->label('Permasalahan')
                    ->words(10)
                    ->tooltip(fn($record) => $record->permasalahan)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('poin')
                    ->badge()
                    ->color(fn(int $state) => match (true) {
                        $state >= 75 => 'danger',
                        $state >= 50 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('pegawai.nama')
                    ->label('Guru BK')
                    ->searchable()
                    ->toggleable(),

            ])

            ->filters([

                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->options(KelasHelper::options())
                    ->query(function ($query, array $data) {

                        if (! filled($data['value'])) {
                            return;
                        }

                        $query->whereHas('siswa.kelasAktif', function ($q) use ($data) {

                            $q->where('kelas_id', $data['value']);
                        });
                    }),

                Filter::make('tanggal')
                    ->form([

                        DatePicker::make('tanggal'),

                    ])

                    ->query(function ($query, array $data) {

                        if ($data['tanggal']) {

                            $query->whereDate(
                                'tanggal',
                                $data['tanggal']
                            );
                        }
                    }),

            ])

            ->recordActions([

                EditAction::make(),

            ])
            ->toolbarActions([

                Action::make('export_pdf')

                    ->label('Export PDF')

                    ->icon('heroicon-o-document-arrow-down')

                    ->color('danger')

                    ->form([

                        Select::make('kelas_id')

                            ->label('Kelas')

                            ->options(
                                Kelas::orderBy('tingkat')
                                    ->orderBy('nama_kelas')
                                    ->pluck('nama_kelas', 'id')
                            )

                            ->live()

                            ->required(),

                        Select::make('siswa_id')

                            ->label('Siswa')

                            ->options(function (Get $get) {

                                if (! $get('kelas_id')) {
                                    return [];
                                }

                                return Siswa::query()

                                    ->whereHas(
                                        'kelasAktif',
                                        fn($q) => $q->where(
                                            'kelas_id',
                                            $get('kelas_id')
                                        )
                                    )

                                    ->orderBy('nama')

                                    ->pluck('nama', 'id');
                            })

                            ->searchable()

                            ->required(),

                    ])

                    ->action(function (array $data) {

                        return redirect()->route(
                            'permasalahan.print',
                            $data['siswa_id']
                        );
                    }),

            ]);
    }
}
