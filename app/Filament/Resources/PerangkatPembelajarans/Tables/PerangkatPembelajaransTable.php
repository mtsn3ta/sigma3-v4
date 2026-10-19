<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Tables;

use App\Filament\Actions\BeriUmpanBalikAction;
use App\Models\PerangkatPembelajaran;
use App\Models\TahunAjaran;
use App\Support\ActiveRole;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PerangkatPembelajaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.nama')
                    ->label('Guru')
                    ->searchable()
                    ->sortable()
                    ->hidden(fn () => ActiveRole::is('guru')),

                TextColumn::make('tahunAjaran.tahun')
                    ->label('Tahun Ajaran')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('jenis')
                    ->label('Jenis Perangkat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Modul Ajar', 'RPP' => 'success',
                        'Silabus', 'Prota', 'Prosem' => 'info',
                        'Bahan Ajar' => 'primary',
                        default => 'warning',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Modul Ajar', 'RPP' => 'heroicon-m-document-text',
                        'Silabus', 'Prota', 'Prosem' => 'heroicon-m-calendar-days',
                        default => 'heroicon-m-folder',
                    }),

                TextColumn::make('mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('judul')
                    ->label('Judul Perangkat')
                    ->searchable()
                    ->wrap()
                    ->limit(50),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Semua Kelas')
                    ->sortable(),

                TextColumn::make('file_path')
                    ->label('Format')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if (blank($state)) return '-';
                        $ext = pathinfo($state, PATHINFO_EXTENSION);
                        return strtoupper($ext ?: 'FILE');
                    })
                    ->color('primary')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Diunggah')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'tahun')
                    ->default(fn () => TahunAjaran::aktifId())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('jenis')
                    ->label('Jenis Perangkat')
                    ->options([
                        'Modul Ajar' => 'Modul Ajar',
                        'RPP'        => 'RPP',
                        'Silabus'    => 'Silabus / ATP',
                        'Prota'      => 'Prota',
                        'Prosem'     => 'Promes',
                        'Bahan Ajar' => 'Bahan Ajar / LKPD',
                        'Lainnya'    => 'Lainnya',
                    ]),

                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload(),
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
                    ->visible(fn ($record): bool => ActiveRole::is('super_admin')
                        || (ActiveRole::is('guru') && $record->pegawai_id === auth()->user()?->pegawai?->id)),

                DeleteAction::make()
                    ->visible(fn ($record): bool => ActiveRole::is('super_admin')
                        || (ActiveRole::is('guru') && $record->pegawai_id === auth()->user()?->pegawai?->id)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => ActiveRole::is('guru') || ActiveRole::is('super_admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->emptyStateHeading('Belum Ada Perangkat Pembelajaran')
            ->emptyStateDescription('Unggah Modul Ajar, RPP, Silabus, atau dokumen ajar lainnya.')
            ->emptyStateIcon('heroicon-o-folder-open');
    }
}
