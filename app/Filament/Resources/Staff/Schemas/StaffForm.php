<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nik_nip')
                    ->label('NIK / NIP')
                    ->maxLength(100),

                TextInput::make('no_hp')
                    ->label('No HP')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('rfid_uid')
                    ->label('RFID UID')
                    ->maxLength(100),

                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),

            ]);
    }
}
