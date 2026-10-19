<?php

namespace App\Filament\Resources\Kelas\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tingkat')
                    ->badge(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([

                SelectFilter::make('tingkat')
                    ->options([
                        7 => 'Kelas 7',
                        8 => 'Kelas 8',
                        9 => 'Kelas 9',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),

            ])

            ->recordActions([
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\Action::make('cetak_qr_a4')
                        ->label('Poster A4 (Pintu/Dinding)')
                        ->icon('heroicon-o-document-text')
                        ->url(fn($record) => route('kelas.print-qr', ['kelas_id' => $record->id, 'format' => 'a4']))
                        ->openUrlInNewTab(),

                    \Filament\Actions\Action::make('cetak_qr_a5')
                        ->label('Kartu A5 (Akrilik Meja Guru)')
                        ->icon('heroicon-o-rectangle-stack')
                        ->url(fn($record) => route('kelas.print-qr', ['kelas_id' => $record->id, 'format' => 'a5']))
                        ->openUrlInNewTab(),

                    \Filament\Actions\Action::make('cetak_qr_stiker')
                        ->label('Stiker Mini')
                        ->icon('heroicon-o-qr-code')
                        ->url(fn($record) => route('kelas.print-qr', ['kelas_id' => $record->id, 'format' => 'stiker']))
                        ->openUrlInNewTab(),
                ])
                    ->label('Cetak QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('info'),

                EditAction::make(),

                DeleteAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation()
                    ->visible(fn($record) => ! $record->kelasSiswa()->exists()),
            ])

            ->toolbarActions([
                //
            ]);
    }
}
