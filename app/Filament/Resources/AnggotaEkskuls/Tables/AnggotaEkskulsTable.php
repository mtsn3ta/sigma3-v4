<?php

namespace App\Filament\Resources\AnggotaEkskuls\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\TahunAjaran;
use Filament\Tables\Filters\SelectFilter;

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

            ->filters([

                SelectFilter::make('tahun_ajaran_id')

                    ->label('Tahun Ajaran')

                    ->relationship(
                        'tahunAjaran',
                        'tahun'
                    )

                    ->searchable()

                    ->preload()

                    ->default(
                        TahunAjaran::query()
                            ->where('aktif', true)
                            ->value('id')
                    ),

                SelectFilter::make('ekstrakurikuler_id')

                    ->label('Ekstrakurikuler')

                    ->relationship(
                        'ekstrakurikuler',
                        'nama'
                    )

                    ->searchable()

                    ->preload(),

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
