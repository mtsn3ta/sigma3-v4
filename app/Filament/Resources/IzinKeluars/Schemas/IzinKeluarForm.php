<?php

namespace App\Filament\Resources\IzinKeluars\Schemas;

use App\Support\KelasHelper;
use App\Support\SiswaHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IzinKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Izin Keluar')

                    ->schema([

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(today())
                            ->required(),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(KelasHelper::options())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('siswa_id', null))
                            ->dehydrated(false)
                            ->required(),

                        Select::make('siswa_id')
                            ->label('Siswa')
                            ->live()
                            ->options(
                                fn($get) => SiswaHelper::byKelas(
                                    $get('kelas_id')
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('jam_keluar')
                            ->label('Jam Keluar')
                            ->type('time')
                            ->default(now()->format('H:i'))
                            ->required(),

                        TextInput::make('tujuan')
                            ->label('Tujuan')
                            ->required()
                            ->maxLength(150)
                            ->datalist([

                                'Dispensasi',

                                'Puskesmas',

                                'Rumah Sakit',

                                'Apotek',

                                'Rumah',

                                'Lainnya',

                            ]),

                        Textarea::make('keperluan')
                            ->label('Keperluan')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull(),

            ]);
    }
}
