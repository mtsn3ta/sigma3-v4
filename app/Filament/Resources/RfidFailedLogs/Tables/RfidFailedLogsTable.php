<?php

namespace App\Filament\Resources\RfidFailedLogs\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class RfidFailedLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort(
                'created_at',
                'desc',
            )

            ->columns([

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),

                TextColumn::make('mode')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'school' => 'primary',
                        'ibadah' => 'success',
                        'ekskul' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'school' => 'Sekolah',
                        'ibadah' => 'Ibadah',
                        'ekskul' => 'Ekstrakurikuler',
                        default => $state,
                    }),

                TextColumn::make('uid')
                    ->label('UID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas')
                    ->placeholder('-'),

                TextColumn::make('error')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('message')
                    ->label('Pesan')
                    ->wrap(),

            ])

            ->filters([

                SelectFilter::make('mode')
                    ->label('Mode')
                    ->options([
                        'school' => 'Sekolah',
                        'ibadah' => 'Ibadah',
                        'ekskul' => 'Ekstrakurikuler',
                    ]),

                Filter::make('tanggal')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn($query, $tanggal) => $query->whereDate(
                                'created_at',
                                $tanggal,
                            ),
                        );
                    }),

            ])

            ->recordActions([])

            ->toolbarActions([]);
    }
}
