<?php

namespace App\Filament\Resources\LaporanBulanans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ApprovalLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'approvalLogs';

    protected static ?string $title = 'Riwayat Approval';

    public function table(Table $table): Table
    {
        return $table

            ->recordTitleAttribute('aksi')

            ->columns([

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('user.pegawai.nama')
                    ->label('Oleh'),

                TextColumn::make('aksi')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {

                        'submit' => 'Submit',

                        'approve_pembina' => 'Approve Pembina',

                        'approve_waka' => 'Approve Waka',

                        'approve_kepala' => 'Approve Kepala',

                        'revision' => 'Revisi',

                        'reject' => 'Reject',

                        default => $state,
                    }),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->wrap(),

            ])

            ->headerActions([])

            ->actions([])

            ->bulkActions([]);
    }
}
