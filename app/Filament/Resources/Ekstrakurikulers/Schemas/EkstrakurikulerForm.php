<?php

namespace App\Filament\Resources\Ekstrakurikulers\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;

class EkstrakurikulerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Ekstrakurikuler')
                    ->schema([

                        TextInput::make('nama')
                            ->required()
                            ->maxLength(150),

                        Textarea::make('deskripsi')
                            ->rows(4),

                        CheckboxList::make('jadwal')
                            ->options([
                                'senin' => 'Senin',
                                'selasa' => 'Selasa',
                                'rabu' => 'Rabu',
                                'kamis' => 'Kamis',
                                'jumat' => 'Jumat',
                                'sabtu' => 'Sabtu',
                            ])
                            ->columns(3)
                            ->columnSpanFull(),

                        TextInput::make('kuota')
                            ->numeric()
                            ->minValue(1),

                    ])
                    ->columns(2),

                Section::make('Status')
                    ->schema([

                        Radio::make('status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->default('aktif')
                            ->required(),

                    ]),
            ]);
    }
}
