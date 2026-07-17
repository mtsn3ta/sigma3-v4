<?php

namespace App\Filament\Resources\Galeris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;


class GalerisTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                ImageColumn::make('cover')

                    ->label('Cover')

                    ->disk('public')

                    ->square()

                    ->defaultImageUrl(
                        asset('images/no-image.png')
                    ),

                TextColumn::make('judul')

                    ->label('Judul')

                    ->searchable()

                    ->sortable(),

                TextColumn::make('foto_count')

                    ->label('Foto')

                    ->counts('foto')

                    ->badge()

                    ->color('primary'),

                TextColumn::make('ekstrakurikuler.nama')

                    ->label('Ekstrakurikuler')

                    ->placeholder('-')

                    ->searchable()

                    ->sortable(),

                TextColumn::make('kategori_label')

                    ->label('Kategori')

                    ->badge()

                    ->color(fn($record) => match ($record->kategori) {

                        'sekolah' => 'gray',

                        'ekstrakurikuler' => 'primary',

                        'prestasi' => 'success',

                        'kegiatan' => 'warning',

                        default => 'gray',
                    }),

                TextColumn::make('tanggal')

                    ->label('Tanggal')

                    ->date('d M Y')

                    ->sortable(),

                TextColumn::make('createdBy.name')

                    ->label('Dibuat Oleh')

                    ->searchable()

                    ->placeholder('-'),

                TextColumn::make('created_at')

                    ->label('Dibuat')

                    ->dateTime('d M Y H:i')

                    ->sortable(),

            ])

            ->defaultSort(
                'tanggal',
                'desc'
            )

            ->filters([

                SelectFilter::make('ekstrakurikuler_id')

                    ->label('Ekstrakurikuler')

                    ->relationship(
                        'ekstrakurikuler',
                        'nama'
                    )

                    ->searchable()

                    ->preload(),

                SelectFilter::make('kategori')

                    ->label('Kategori')

                    ->options([

                        'sekolah' => 'Sekolah',

                        'ekstrakurikuler' => 'Ekstrakurikuler',

                        'prestasi' => 'Prestasi',

                        'kegiatan' => 'Kegiatan',

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
