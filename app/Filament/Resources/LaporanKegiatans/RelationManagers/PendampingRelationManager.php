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

    protected static ?string $title = 'Daftar Kehadiran Pendamping';

    public function isReadOnly(): bool
    {
        return $this->ownerRecord->isLocked();
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
                    ->native(false)
                    ->required(),

                Textarea::make('keterangan')
                    ->label('Keterangan / Alasan')
                    ->placeholder('Catatan jika berhalangan hadir')
                    ->rows(3),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')

            ->columns([

                TextColumn::make('pegawai.nama')
                    ->label('Nama Pendamping')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

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
                    })
                    ->color(fn(string $state) => match ($state) {
                        'pembina' => 'info',
                        'pelatih' => 'success',
                        'guru_pendamping' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Kehadiran')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state) => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('keterangan')
                    ->label('Catatan')
                    ->placeholder('-')
                    ->limit(40),

            ])

            ->filters([
                //
            ])

            ->headerActions([
                //
            ])

            ->recordActions([

                EditAction::make()
                    ->modalWidth('md')
                    ->modalHeading('Ubah Status Kehadiran Pendamping'),

            ])

            ->toolbarActions([
                //
            ]);
    }
}
