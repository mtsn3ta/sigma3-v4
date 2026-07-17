<?php

namespace App\Filament\Resources\Prestasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class PrestasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort(
                'tanggal',
                'desc'
            )
            ->columns([

                ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),

                TextColumn::make('nama_prestasi')
                    ->label('Prestasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_label')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Individu' => 'primary',
                        'Tim' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->color('warning')
                    ->searchable(),

                TextColumn::make('siswa_count')
                    ->label('Peserta')
                    ->counts('siswa')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])
            ->filters([

                SelectFilter::make('ekstrakurikuler_id')

                    ->label('Ekstrakurikuler')

                    ->relationship(
                        'ekstrakurikuler',
                        'nama'
                    )

                    ->searchable()

                    ->preload(),

                SelectFilter::make('jenis')

                    ->options([

                        'individu' => 'Individu',

                        'tim' => 'Tim',

                    ]),

            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
