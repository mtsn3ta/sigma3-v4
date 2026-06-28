<?php

namespace App\Filament\Resources\IzinKeluars\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\IzinKeluar;
use Filament\Actions\Action;

class IzinKeluarsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort('tanggal', 'desc')
            ->columns([

                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas')
                    ->badge(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jam_keluar_formatted')
                    ->label('Keluar'),

                TextColumn::make('jam_kembali_formatted')
                    ->label('Kembali')
                    ->placeholder('-'),

                TextColumn::make('tujuan')
                    ->label('Tujuan')
                    ->searchable(),

                TextColumn::make('waktu_cetak')
                    ->label('Dicetak')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextColumn::make('status_proses')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state) => match ($state) {
                        'keluar' => 'heroicon-o-arrow-right-circle',
                        'kembali' => 'heroicon-o-arrow-uturn-left',
                        'pulang' => 'heroicon-o-home',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn(string $state) => match ($state) {
                        'keluar'  => 'warning',
                        'kembali' => 'success',
                        'pulang'  => 'info',
                        default   => 'gray',
                    }),

                TextColumn::make('pegawai.nama')
                    ->label('Petugas')
                    ->searchable(),

            ])

            ->recordActions([

                Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->openUrlInNewTab()
                    ->url(fn(IzinKeluar $record) => route(
                        'thermal.izin-keluar',
                        $record
                    )),

                Action::make('kembali')
                    ->label('Kembali')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->visible(fn(IzinKeluar $record) => $record->isKeluar())
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Siswa Kembali')
                    ->modalDescription(function (IzinKeluar $record): string {

                        return <<<TEXT
Nama      : {$record->siswa->nama}

Kelas     : {$record->siswa->nama_kelas_aktif}

Jam Keluar: {$record->jam_keluar_formatted}

Tekan "Konfirmasi" jika siswa telah kembali ke madrasah.
TEXT;
                    })
                    ->modalSubmitActionLabel('Konfirmasi')
                    ->action(function (IzinKeluar $record) {

                        $record->update([
                            'status_proses' => 'kembali',
                            'jam_kembali'   => now()->format('H:i:s'),
                        ]);
                    }),

                Action::make('pulang')
                    ->label('Pulang')
                    ->icon('heroicon-o-home')
                    ->color('info')
                    ->visible(fn(IzinKeluar $record) => $record->isKeluar())
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Siswa Pulang')
                    ->modalDescription(function (IzinKeluar $record): string {

                        return <<<TEXT
Nama      : {$record->siswa->nama}

Kelas     : {$record->siswa->nama_kelas_aktif}

Jam Keluar: {$record->jam_keluar_formatted}

Gunakan opsi ini apabila siswa tidak kembali ke madrasah pada hari yang sama.
TEXT;
                    })
                    ->modalSubmitActionLabel('Konfirmasi')
                    ->action(function (IzinKeluar $record) {

                        $record->update([
                            'status_proses' => 'pulang',
                            'jam_kembali'   => now()->format('H:i:s'),
                        ]);
                    }),

                EditAction::make()
                    ->visible(
                        fn(IzinKeluar $record) =>
                        $record->isKeluar()
                            && blank($record->waktu_cetak)
                    ),

            ])

            ->toolbarActions([]);
    }
}
