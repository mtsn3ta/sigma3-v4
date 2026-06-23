<?php

namespace App\Filament\Resources\KehadiranEkskuls\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KehadiranEkskulsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('sesiKegiatan.tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('sesiKegiatan.ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable(),

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('sumber')
                    ->badge(),

                TextColumn::make('waktu_scan')
                    ->label('Waktu Scan')
                    ->dateTime('d M Y H:i'),

            ])
            ->filters([])

            ->recordActions([
                EditAction::make(),
            ])
            ->headerActions([
                //
            ])

            ->toolbarActions([]);
    }
}
