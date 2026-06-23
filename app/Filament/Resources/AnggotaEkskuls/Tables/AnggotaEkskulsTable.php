<?php

namespace App\Filament\Resources\AnggotaEkskuls\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnggotaEkskulsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->sortable(),

                TextColumn::make('tanggal_daftar')
                    ->date('d M Y')
                    ->sortable(),

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
