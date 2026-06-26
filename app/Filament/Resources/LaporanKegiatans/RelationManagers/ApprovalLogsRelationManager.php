<?php

namespace App\Filament\Resources\LaporanKegiatans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalLogRelationManager extends RelationManager
{
    protected static string $relationship = 'approvalLogs';

    protected static ?string $title = 'Timeline Approval';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->defaultSort('created_at')

            ->columns([

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since(),

                TextColumn::make('user.name')
                    ->label('Pengguna'),

                TextColumn::make('action')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {

                        'submit' => 'Submit',

                        'approve_pembina' => 'Approve Pembina',

                        'approve_waka' => 'Approve Waka',

                        'approve_kepala' => 'Approve Kepala',

                        'revision' => 'Request Revision',

                        'reject' => 'Reject',

                        default => ucfirst($state),
                    }),

                TextColumn::make('catatan')
                    ->limit(60),

            ])

            ->headerActions([])

            ->recordActions([])

            ->toolbarActions([]);
    }
}
