<?php

namespace App\Filament\Resources\ProposalKegiatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProposalKegiatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_kegiatan')
                    ->label('Nama Kegiatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('ekstrakurikuler.nama')
                    ->label('Ekstrakurikuler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_kegiatan_label')
                    ->label('Jenis')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('peserta_count')
                    ->label('Peserta')
                    ->counts('peserta')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_anggaran')
                    ->label('Anggaran')
                    ->money('IDR', divideBy: 1)
                    ->sortable(),

                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('createdBy.pegawai.nama')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        'draft' => 'gray',
                        'pending_pembina' => 'warning',
                        'pending_waka' => 'warning',
                        'pending_kepala' => 'warning',
                        'revision' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->defaultSort('created_at', 'desc')

            ->filters([])

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
