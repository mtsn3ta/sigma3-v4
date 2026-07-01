<?php

namespace App\Filament\Resources\Kelas\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kelas')
                    ->description('Kelola data tingkat dan status kelas.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        TextInput::make('nama_kelas')
                            ->label('Nama Kelas')
                            ->placeholder('Contoh: 7A')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->columnSpanFull(),

                        Radio::make('tingkat')
                            ->label('Tingkat Kelas')
                            ->options([
                                7 => 'Kelas 7',
                                8 => 'Kelas 8',
                                9 => 'Kelas 9',
                            ])
                            ->inline() // Dibuat mendatar agar hemat ruang
                            ->required(),

                        Radio::make('status')
                            ->label('Status Kelas')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->inline() // Dibuat mendatar agar hemat ruang
                            ->default('aktif')
                            ->required(),
                    ])
                    ->columnSpanFull(), // Membagi tampilan menjadi 2 kolom agar seimbang
            ]);
    }
}
