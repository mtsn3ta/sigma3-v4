<?php

namespace App\Filament\Resources\LaporanKegiatans\RelationManagers;

use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PesertaRelationManager extends RelationManager
{
    protected static string $relationship = 'peserta';

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
                        'alpha' => 'Alpha',
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

                TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->searchable(),

                TextColumn::make('siswa.nama')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        'alpha' => 'danger',
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
