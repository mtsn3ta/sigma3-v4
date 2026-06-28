<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Tables;

use App\Support\RoleHelper;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EkstrakurikulerPengampusTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pegawai.nama')
                    ->label('Pegawai')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([])

            ->recordActions([

                DeleteAction::make()

                    ->after(function ($record) {

                        $pegawai = $record->pegawai;

                        if (! $pegawai?->user) {
                            return;
                        }

                        RoleHelper::removePengampuRoleIfUnused(
                            $pegawai->user,
                            $pegawai->id,
                            $record->role
                        );
                    }),

            ])

            ->toolbarActions([]);
    }
}
