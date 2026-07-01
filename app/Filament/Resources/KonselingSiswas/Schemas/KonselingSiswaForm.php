<?php

namespace App\Filament\Resources\KonselingSiswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Support\KelasHelper;
use App\Support\SiswaHelper;

class KonselingSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Konseling')

                    ->schema([

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->required()
                            ->default(now()),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(KelasHelper::options())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn($set) => $set('siswa_id', null))
                            ->required(),

                        Select::make('siswa_id')
                            ->label('Siswa')
                            ->options(fn($get) => SiswaHelper::byKelas($get('kelas_id')))
                            ->searchable()
                            ->disabled(fn($get) => blank($get('kelas_id')))
                            ->placeholder('Pilih siswa')
                            ->required(),

                        Textarea::make('permasalahan')
                            ->label('Permasalahan')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('hasil_konseling')
                            ->label('Hasil Konseling')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('tindak_lanjut')
                            ->label('Tindak Lanjut')
                            ->rows(5)
                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull(),

            ]);
    }
}
