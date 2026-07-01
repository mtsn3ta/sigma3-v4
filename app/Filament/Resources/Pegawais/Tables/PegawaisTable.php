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
                    ->circular()
                    ->size(40), // Ukuran pas, tidak terlalu besar/kecil

                TextColumn::make('nama')
                    ->weight('bold') // Font lebih tebal untuk nama
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nik_nip')
                    ->label('NIK / NIP')
                    ->searchable()
                    ->fontFamily('mono') // Memberikan kesan teknis/data yang rapi
                    ->size('sm'),

                TextColumn::make('jenis_pegawai')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'internal' => 'primary',
                        'eksternal' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('no_hp')
                    ->icon('heroicon-m-device-phone-mobile') // Ikon membuat tabel lebih hidup
                    ->color('gray'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('user.roles.name')
                    ->badge()
                    ->separator(',')
                    ->color('info'), // Warna biru lembut untuk role

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
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
