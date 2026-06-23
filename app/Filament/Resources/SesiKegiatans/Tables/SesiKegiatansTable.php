<?php

namespace App\Filament\Resources\SesiKegiatans\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SesiKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pelatih.nama')
                    ->label('Pelatih')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('waktu_mulai')
                    ->label('Mulai')
                    ->time('H:i'),

                TextColumn::make('waktu_selesai')
                    ->label('Selesai')
                    ->time('H:i'),

                TextColumn::make('status')
                    ->badge(),

            ])

            ->filters([])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([]);
    }
}
