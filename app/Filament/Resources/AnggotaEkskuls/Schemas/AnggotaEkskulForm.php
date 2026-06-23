<?php

namespace App\Filament\Resources\AnggotaEkskuls\Schemas;

use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AnggotaEkskulForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Anggota')
                    ->schema([

                        Select::make('siswa_id')
                            ->relationship('siswa', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('ekstrakurikuler_id')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('tahun_ajaran_id')
                            ->relationship('tahunAjaran', 'tahun')
                            ->default(
                                TahunAjaran::where('aktif', true)->value('id')
                            )
                            ->searchable()
                            ->required(),

                        DatePicker::make('tanggal_daftar')
                            ->default(now())
                            ->native(false)
                            ->required(),

                        Radio::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->default('aktif')
                            ->required(),

                    ])
                    ->columns(2),

            ]);
    }
}
