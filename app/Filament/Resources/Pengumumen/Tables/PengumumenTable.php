<?php

namespace App\Filament\Resources\Pengumumen\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class PengumumenTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('is_pinned')
                    ->label('Urgent Alert')
                    ->badge()
                    ->formatStateUsing(fn(bool $state) => $state ? 'YA' : 'TIDAK')
                    ->color(fn(bool $state) => $state ? 'danger' : 'gray'),

                TextColumn::make('judul')

                    ->label('Judul')

                    ->searchable()

                    ->sortable()

                    ->wrap(),

                TextColumn::make('ekstrakurikuler.nama')

                    ->label('Ekstrakurikuler')

                    ->placeholder('Sekolah')

                    ->searchable()

                    ->sortable(),

                TextColumn::make('tipe_label')

                    ->label('Tipe')

                    ->badge()

                    ->color(fn($record) => match ($record->tipe) {

                        'info' => 'info',

                        'success' => 'success',

                        'warning' => 'warning',

                        'danger' => 'danger',

                        default => 'gray',
                    }),

                TextColumn::make('status_label')

                    ->label('Status')

                    ->badge()

                    ->color(fn($record) => match ($record->status) {

                        'draft' => 'gray',

                        'publish' => 'success',

                        'arsip' => 'danger',

                        default => 'gray',
                    }),

                TextColumn::make('tanggal_mulai')

                    ->label('Mulai')

                    ->date('d M Y')

                    ->sortable(),

                TextColumn::make('tanggal_selesai')

                    ->label('Selesai')

                    ->date('d M Y')

                    ->sortable(),

                TextColumn::make('createdBy.name')

                    ->label('Dibuat Oleh')

                    ->placeholder('-')

                    ->searchable(),

                TextColumn::make('created_at')

                    ->label('Dibuat')

                    ->dateTime('d M Y H:i')

                    ->sortable(),

            ])

            ->defaultSort(
                'tanggal_mulai',
                'desc'
            )

            ->filters([

                SelectFilter::make('status')

                    ->options([

                        'draft' => 'Draft',

                        'publish' => 'Publish',

                        'arsip' => 'Arsip',

                    ]),

                SelectFilter::make('tipe')

                    ->options([

                        'info' => 'Info',

                        'success' => 'Success',

                        'warning' => 'Warning',

                        'danger' => 'Danger',

                    ]),

                SelectFilter::make('ekstrakurikuler_id')

                    ->relationship(
                        'ekstrakurikuler',
                        'nama'
                    )

                    ->searchable()

                    ->preload(),

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
