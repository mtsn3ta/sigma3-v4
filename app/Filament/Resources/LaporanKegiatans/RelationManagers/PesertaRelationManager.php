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

    protected static ?string $title = 'Daftar Kehadiran Peserta';

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
                        'alpha' => 'Alpha',
                    ])
                    ->native(false)
                    ->required(),

                Textarea::make('keterangan')
                    ->label('Keterangan / Alasan')
                    ->placeholder('Catatan jika berhalangan hadir atau dispensasi')
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
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('siswa.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('siswa.nama_kelas_aktif')
                    ->label('Kelas')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label('Kehadiran')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                        default => ucfirst($state),
                    })
                    ->color(fn(string $state) => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        'alpha' => 'danger',
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
                    ->modalHeading('Ubah Status Kehadiran Peserta'),

            ])

            ->toolbarActions([
                //
            ]);
    }
}
