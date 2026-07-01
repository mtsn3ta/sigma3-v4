<?php

namespace App\Filament\Resources\SesiKegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

class SesiKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Sesi Kegiatan')
                    ->description('Tentukan ekstrakurikuler, pelatih, dan detail waktu pelaksanaan.')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpanFull()
                    ->schema([
                        // Entitas Utama
                        Select::make('ekstrakurikuler_id')
                            ->label('Ekstrakurikuler')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('pelatih_id')
                            ->label('Pelatih')
                            ->relationship('pelatih', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Kegiatan')
                            ->default(now())
                            ->native(false)
                            ->required(),

                        // Pengaturan Waktu
                        Grid::make(2)->schema([
                            TimePicker::make('waktu_mulai')
                                ->label('Waktu Mulai')
                                ->seconds(false)
                                ->required(),

                            TimePicker::make('waktu_selesai')
                                ->label('Waktu Selesai')
                                ->seconds(false)
                                ->required(),
                        ]),

                        Textarea::make('program_latihan')
                            ->label('Program Latihan')
                            ->placeholder('Tuliskan detail materi atau target latihan hari ini...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Radio::make('status')
                            ->label('Status Sesi')
                            ->options([
                                'aktif' => 'Aktif / Berjalan',
                                'selesai' => 'Selesai',
                                'batal' => 'Dibatalkan',
                            ])
                            ->inline()
                            ->default('aktif')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
