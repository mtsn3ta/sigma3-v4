<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TahunAjaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konfigurasi Tahun Ajaran')
                    ->description('Tentukan tahun ajaran dan status aktif sistem.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        TextInput::make('tahun')
                            ->label('Tahun Ajaran')
                            ->placeholder('Contoh: 2026/2027')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->columnSpanFull(), // Agar input lebih lega

                        Toggle::make('aktif')
                            ->label('Jadikan sebagai Tahun Ajaran Aktif')
                            ->helperText('Hanya satu tahun ajaran yang boleh aktif dalam satu waktu.')
                            ->onColor('success') // Warna hijau saat aktif
                            ->default(false),
                    ])
                    ->columnSpanFull(), // Karena cuma dua elemen, satu kolom sudah sangat rapi
            ]);
    }
}
