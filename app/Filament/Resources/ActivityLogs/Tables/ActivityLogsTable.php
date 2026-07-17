<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort('created_at', 'desc')

            ->columns([

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('aksi')
                    ->label('Aksi')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(80)
                    ->tooltip(fn($record) => $record->deskripsi)
                    ->wrap(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->toggleable(),

            ])

            ->filters([])

            ->recordActions([

                ViewAction::make(),

            ])

            ->toolbarActions([]);
    }
}
