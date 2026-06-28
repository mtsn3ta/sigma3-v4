<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EkstrakurikulerPengampuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Pengampu')

                    ->schema([

                        Select::make('ekstrakurikuler_id')
                            ->label('Ekstrakurikuler')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('pegawai_id')
                            ->label('Pegawai')
                            ->relationship('pegawai', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        CheckboxList::make('roles')
                            ->label('Role Pengampu')
                            ->options([
                                'pembina' => 'Pembina',
                                'pelatih' => 'Pelatih',
                            ])
                            ->columns(2)
                            ->required(),

                    ])

                    ->columns(2),

            ]);
    }
}
