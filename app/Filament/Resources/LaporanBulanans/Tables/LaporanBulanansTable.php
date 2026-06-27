<?php

namespace App\Filament\Resources\LaporanBulanans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LaporanBulanansTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bulan')
                    ->badge(),

                TextColumn::make('tahun')
                    ->sortable(),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->placeholder('-'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {

                        'draft' => 'gray',

                        'pending_pembina' => 'warning',

                        'pending_waka' => 'warning',

                        'pending_kepala' => 'warning',

                        'revision' => 'warning',

                        'approved' => 'success',

                        'rejected' => 'danger',

                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->defaultSort('tahun', 'desc')

            ->filters([])

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
