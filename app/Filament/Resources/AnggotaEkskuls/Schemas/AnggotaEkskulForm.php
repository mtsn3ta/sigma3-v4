<?php

namespace App\Filament\Resources\AnggotaEkskuls\Schemas;

use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

class AnggotaEkskulForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pendaftaran Anggota Ekstrakurikuler')
                    ->description('Isi data siswa dan pilih ekstrakurikuler yang diikuti.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->columnSpanFull()
                    ->schema([
                        // Baris 1: Entitas Utama
                        Grid::make(2)->schema([
                            Select::make('siswa_id')
                                ->label('Pilih Siswa')
                                ->relationship('siswa', 'nama', fn ($query) => $query->where('status', 'aktif'))
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('ekstrakurikuler_id')
                                ->label('Pilih Ekstrakurikuler')
                                ->relationship('ekstrakurikuler', 'nama')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),

                        // Baris 2: Detail Administratif
                        Grid::make(2)->schema([
                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->relationship('tahunAjaran', 'tahun')
                                ->default(TahunAjaran::where('aktif', true)->value('id'))
                                ->searchable()
                                ->required(),

                            DatePicker::make('tanggal_daftar')
                                ->label('Tanggal Daftar')
                                ->default(now())
                                ->native(false)
                                ->required(),
                        ]),

                        // Baris 3: Status
                        Radio::make('status')
                            ->label('Status Keanggotaan')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->inline()
                            ->default('aktif')
                            ->required(),
                    ]),
            ]);
    }
}
