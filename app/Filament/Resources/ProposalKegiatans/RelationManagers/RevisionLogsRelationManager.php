<?php

namespace App\Filament\Resources\ProposalKegiatans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class RevisionLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisionLogs';

    protected static ?string $title = 'Catatan Revisi';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table

            ->columns([

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('user.pegawai.nama')
                    ->label('Direvisi Oleh')
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('status_from')
                    ->label('Dari')
                    ->badge(),

                TextColumn::make('status_to')
                    ->label('Ke')
                    ->badge(),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->wrap(),

            ]);
    }
}
