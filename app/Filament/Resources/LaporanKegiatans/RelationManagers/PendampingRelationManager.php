<?php

namespace App\Filament\Resources\LaporanKegiatans\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PendampingRelationManager extends RelationManager
{
    protected static string $relationship = 'pendamping';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ])
                    ->required(),

                Textarea::make('keterangan')
                    ->rows(3),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')

            ->columns([

                TextColumn::make('pegawai.nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('peran')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pembina' => 'Pembina',
                        'pelatih' => 'Pelatih',
                        'guru_pendamping' => 'Guru Pendamping',
                        'official' => 'Official',
                        'medis' => 'Medis',
                        'transportasi' => 'Transportasi',
                        default => ucfirst($state),
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('keterangan')
                    ->limit(40),

            ])

            ->filters([
                //
            ])

            ->headerActions([
                //
            ])

            ->recordActions([

                EditAction::make(),

            ])

            ->toolbarActions([
                //
            ]);
    }
}
