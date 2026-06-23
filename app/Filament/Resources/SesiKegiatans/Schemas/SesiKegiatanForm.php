<?php

namespace App\Filament\Resources\SesiKegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SesiKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Sesi Kegiatan')
                    ->schema([

                        Select::make('ekstrakurikuler_id')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('pelatih_id')
                            ->relationship('pelatih', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal')
                            ->default(now())
                            ->native(false)
                            ->required(),

                        TimePicker::make('waktu_mulai')
                            ->seconds(false)
                            ->required(),

                        TimePicker::make('waktu_selesai')
                            ->seconds(false)
                            ->required(),

                        Radio::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'selesai' => 'Selesai',
                                'batal' => 'Batal',
                            ])
                            ->default('aktif')
                            ->required(),

                    ])
                    ->columns(2),

            ]);
    }
}
