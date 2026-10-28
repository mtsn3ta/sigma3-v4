<?php

namespace App\Filament\Resources\AgendaMadrasahs\Tables;

use App\Models\AgendaMadrasah;
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
                TextColumn::make('tanggal_mulai')
                    ->label('Periode Tanggal')
                    ->formatStateUsing(fn($state, AgendaMadrasah $record): string => $record->periode_teks)
                    ->description(fn(AgendaMadrasah $record): ?string => $record->durasi_hari > 1 ? "({$record->durasi_hari} Hari)" : null)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama_agenda')
                    ->label('Nama Acara')
                    ->weight('bold')
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
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40)
                    ->tooltip(fn(AgendaMadrasah $record): ?string => $record->keterangan)
                    ->placeholder('-'),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
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
