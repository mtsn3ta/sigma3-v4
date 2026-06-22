<?php

namespace App\Filament\Resources\Pegawais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PegawaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('nama')
            ->columns([

                ImageColumn::make('foto')
                    ->circular(),

                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nik_nip')
                    ->label('NIK/NIP')
                    ->searchable(),

                TextColumn::make('jenis_pegawai')
                    ->badge()
                    ->colors([
                        'primary' => 'internal',
                        'warning' => 'eksternal',
                    ]),

                TextColumn::make('no_hp'),

                TextColumn::make('rfid_uid')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'nonaktif',
                    ]),

                TextColumn::make('user.roles.name')
                    ->badge()
                    ->separator(','),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('jenis_pegawai')
                    ->options([
                        'internal' => 'Internal',
                        'eksternal' => 'Eksternal',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
