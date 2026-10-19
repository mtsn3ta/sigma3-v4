<?php

namespace App\Filament\Resources\LaporanKegiatans\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApprovalLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'approvalLogs';

    protected static ?string $title = 'Riwayat Persetujuan (Approval Log)';

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

            ->defaultSort('created_at', 'desc')

            ->columns([

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->dateTimeTooltip(),

                TextColumn::make('user.name')
                    ->label('Nama Pengguna')
                    ->weight('medium'),

                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'submit' => 'Diajukan',
                        'approve_pembina' => 'Disetujui Pembina',
                        'approve_waka' => 'Disetujui Waka',
                        'approve_kepala' => 'Disetujui Kamad',
                        'revision' => 'Minta Revisi',
                        'reject' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state) => match ($state) {
                        'submit' => 'info',
                        'approve_pembina', 'approve_waka', 'approve_kepala' => 'success',
                        'revision' => 'warning',
                        'reject' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('catatan')
                    ->label('Catatan / Alasan')
                    ->placeholder('-')
                    ->wrap(),

            ])

            ->headerActions([])

            ->recordActions([])

            ->toolbarActions([]);
    }
}
