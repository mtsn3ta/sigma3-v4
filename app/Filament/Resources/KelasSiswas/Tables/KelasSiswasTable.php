<?php

namespace App\Filament\Resources\KelasSiswas\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KelasSiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable(),

                TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'naik' => 'Naik',
                        'lulus' => 'Lulus',
                    ]),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([]);
    }
}
