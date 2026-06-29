<?php

namespace App\Filament\Resources\SesiKegiatans\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use App\Filament\Resources\SesiKegiatans\Pages;

class SesiKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pelatih.nama')
                    ->label('Pelatih')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('program_latihan')
                    ->label('Program Latihan')
                    ->limit(60)
                    ->wrap()
                    ->tooltip(fn($record) => $record->program_latihan),

                TextColumn::make('jumlah_hadir')
                    ->label('Hadir')
                    ->state(function ($record) {
                        return $record->kehadiran()
                            ->where('status', 'hadir')
                            ->count();
                    })
                    ->badge()
                    ->color('success'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'aktif' => 'primary',
                        'selesai' => 'success',
                        'batal' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([])

            ->recordActions([

                Action::make('absensi')

                    ->label('Absensi')

                    ->icon('heroicon-o-clipboard-document-check')

                    ->color('success')

                    ->visible(fn($record) => $record->status === 'aktif')

                    ->url(fn($record) => Pages\Absensi::getUrl([
                        'record' => $record,
                    ])),

                Action::make('lihat_absensi')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn($record) => $record->status === 'selesai')
                    ->url(fn($record) => '#'),

                EditAction::make(),

                DeleteAction::make()

                    ->label('Hapus')

                    ->disabled(fn($record) => $record->kehadiran()->exists())

                    ->tooltip(fn($record) => $record->kehadiran()->exists()
                        ? 'Sesi yang sudah memiliki data kehadiran tidak dapat dihapus.'
                        : null)

                    ->requiresConfirmation(),

            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
