<?php

namespace App\Filament\Resources\AgendaMadrasahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class AgendaMadrasahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_agenda')
                    ->label('Nama Acara')
                    ->searchable(),

                TextColumn::make('tipe_agenda')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'libur_nasional' => 'danger',     // Merah
                        'kegiatan_bersama' => 'success',  // Hijau
                        'kondisi_kelas' => 'warning',     // Kuning
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'libur_nasional' => 'Libur Nasional',
                        'kegiatan_bersama' => 'Kegiatan Bersama',
                        'kondisi_kelas' => 'Kondisi Kelas',
                        default => $state,
                    }),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->placeholder('-'), // Jika null (kegiatan bersama / libur) akan tampil tanda strip
            ])
            ->defaultSort('tanggal', 'desc') // Mengurutkan dari tanggal paling baru
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
