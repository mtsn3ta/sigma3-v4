<?php

namespace App\Filament\Resources\Alumnis\Tables;

use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

class AlumnisTable
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

            ])

            ->filters([

                //

            ])

            ->actions([

                Action::make('riwayatAkademik')
                    ->label('Riwayat Akademik')
                    ->icon('heroicon-o-book-open')
                    ->color('info')
                    ->modalHeading('Riwayat Akademik')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->infolist(function (Schema $infolist) {
                        return $infolist->schema([
                            Section::make('Riwayat Kelas')
                                ->schema([
                                    RepeatableEntry::make('kelasSiswa')
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('tahunAjaran.tahun')
                                                ->label('Tahun Ajaran'),

                                            TextEntry::make('kelas.nama_kelas')
                                                ->label('Kelas'),
                                        ])
                                        ->columns(2),
                                ]),
                            Section::make('Prestasi')
                                ->schema([
                                    RepeatableEntry::make('prestasi')
                                        ->hiddenLabel()
                                        ->schema([
                                            TextEntry::make('nama_prestasi')
                                                ->label('Prestasi'),

                                            TextEntry::make('nama_lomba')
                                                ->label('Lomba'),

                                            TextEntry::make('tingkat')
                                                ->label('Tingkat'),

                                            TextEntry::make('tanggal')
                                                ->label('Tanggal')
                                                ->date('d M Y'),

                                        ])
                                        ->columns(2),
                                ]),
                        ]);
                    }),

            ])

            ->bulkActions([]);
    }
}
