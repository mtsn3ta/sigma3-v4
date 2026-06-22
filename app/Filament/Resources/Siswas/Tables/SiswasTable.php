<?php

namespace App\Filament\Resources\Siswas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('foto')
                    ->circular(),

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),

                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->badge(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('user.roles.name')
                    ->label('Role')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'mutasi' => 'Mutasi',
                        'lulus' => 'Lulus',
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
