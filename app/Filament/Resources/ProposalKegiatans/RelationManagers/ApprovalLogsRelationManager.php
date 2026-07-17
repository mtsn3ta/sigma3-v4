<?php

namespace App\Filament\Resources\ProposalKegiatans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'approvalLogs';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                TextColumn::make('action')
                    ->badge()
                    ->colors([
                        'primary' => 'submit',
                        'success' => [
                            'approve_pembina',
                            'approve_waka',
                            'approve_kepala',
                        ],
                        'danger' => 'reject',
                    ]),

                TextColumn::make('notes')
                    ->limit(50),

            ])
            ->defaultSort('created_at');
    }
}
