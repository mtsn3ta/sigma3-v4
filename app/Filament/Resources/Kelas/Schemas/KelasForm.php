<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->placeholder('7A'),

                Radio::make('tingkat')
                    ->options([
                        7 => 'Kelas 7',
                        8 => 'Kelas 8',
                        9 => 'Kelas 9',
                    ])
                    ->required(),

                Radio::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif')
                    ->required(),

            ]);
    }
}
