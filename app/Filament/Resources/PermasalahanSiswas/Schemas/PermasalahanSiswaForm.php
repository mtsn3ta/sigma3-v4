<?php

namespace App\Filament\Resources\PermasalahanSiswas\Schemas;

use App\Support\KelasHelper;
use App\Support\SiswaHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermasalahanSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Permasalahan')

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
                            ->afterStateUpdated(fn(Set $set) => $set('siswa_id', null))
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

                        Textarea::make('penyelesaian')
                            ->label('Penyelesaian')
                            ->rows(5)
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('poin')
                            ->label('Poin')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                    ])

                    ->columnSpanFull(),

            ]);
    }
}
