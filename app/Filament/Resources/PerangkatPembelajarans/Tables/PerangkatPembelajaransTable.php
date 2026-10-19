<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use App\Filament\Actions\BeriUmpanBalikAction;

class PerangkatPembelajaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'RPP', 'Modul Ajar' => 'success',
                        'Silabus', 'Prota', 'Prosem' => 'info',
                        default => 'warning',
                    }),
                TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->searchable(),
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Unggah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => Storage::disk('public')->url($record->file_path), true)
                    ->visible(fn ($record) => !empty($record->file_path)),
                BeriUmpanBalikAction::make('perangkat'),
                EditAction::make()
                    ->visible(fn ($record): bool => \App\Support\ActiveRole::is('super_admin')
                        || (\App\Support\ActiveRole::is('guru') && $record->pegawai_id === auth()->user()?->pegawai?->id)),
                DeleteAction::make()
                    ->visible(fn ($record): bool => \App\Support\ActiveRole::is('super_admin')
                        || (\App\Support\ActiveRole::is('guru') && $record->pegawai_id === auth()->user()?->pegawai?->id)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => \App\Support\ActiveRole::is('guru') || \App\Support\ActiveRole::is('super_admin')),
                ]),
            ]);
    }
}
