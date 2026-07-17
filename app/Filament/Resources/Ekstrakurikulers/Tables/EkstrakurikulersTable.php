<?php

namespace App\Filament\Resources\Ekstrakurikulers\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EkstrakurikulersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jadwal')
                    ->state(function ($record) {

                        $hari = [
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                        ];

                        return collect($record->jadwal)
                            ->map(fn($item) => $hari[$item] ?? $item)
                            ->implode(', ');
                    }),

                TextColumn::make('kuota')
                    ->alignCenter(),

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
                        'nonaktif' => 'Nonaktif',
                    ]),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([]);
    }
}
