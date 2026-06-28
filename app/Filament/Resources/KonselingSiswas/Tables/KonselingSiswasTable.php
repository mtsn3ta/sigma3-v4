<?php

namespace App\Filament\Resources\KonselingSiswas\Tables;

use App\Support\KelasHelper;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KonselingSiswasTable
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
                    ->badge()
                    ->sortable(),
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
                //
            ]);
    }
}
