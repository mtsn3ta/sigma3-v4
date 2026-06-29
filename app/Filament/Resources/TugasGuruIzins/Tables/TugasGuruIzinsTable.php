<?php

namespace App\Filament\Resources\TugasGuruIzins\Tables;

use App\Models\TugasGuruIzin;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TugasGuruIzinsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort('tanggal', 'desc')

            ->columns([

                TextColumn::make('guru.nama')
                    ->label('Guru')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge(),

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jam_ke')
                    ->label('Jam'),

                TextColumn::make('mapel')
                    ->label('Mapel')
                    ->searchable(),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn(TugasGuruIzin $record) => match ($record->status) {

                        'baru' => 'warning',

                        'disampaikan' => 'success',

                        default => 'gray',
                    }),

                TextColumn::make('petugas.nama')
                    ->label('Disampaikan Oleh')
                    ->placeholder('-'),

            ])

            ->filters([

                //

            ])

            ->recordActions([

                Action::make('print')

                    ->label('Cetak')

                    ->icon('heroicon-o-printer')

                    ->color('primary')

                    ->openUrlInNewTab()

                    ->url(fn(TugasGuruIzin $record) => route(
                        'thermal.tugas-guru-izin',
                        $record
                    )),

                Action::make('disampaikan')

                    ->label('Sudah Disampaikan')

                    ->icon('heroicon-o-check-circle')

                    ->color('success')

                    ->visible(
                        fn(TugasGuruIzin $record) =>
                        $record->status === 'baru'
                    )

                    ->requiresConfirmation()

                    ->modalHeading('Konfirmasi Penyampaian Tugas')

                    ->modalDescription(function (TugasGuruIzin $record): string {

                        return <<<TEXT
Guru     : {$record->guru->nama}

Kelas    : {$record->kelas->nama_kelas}

Tanggal  : {$record->tanggal->format('d M Y')}

Jam      : {$record->jam_ke}

Mapel    : {$record->mapel}

Tekan "Konfirmasi" jika tugas telah disampaikan.
TEXT;
                    })

                    ->modalSubmitActionLabel('Konfirmasi')

                    ->action(function (TugasGuruIzin $record) {

                        $record->update([

                            'status' => 'disampaikan',

                            'petugas_id' => Auth::user()->pegawai->id,

                        ]);
                    }),

            ])

            ->toolbarActions([]);
    }
}
