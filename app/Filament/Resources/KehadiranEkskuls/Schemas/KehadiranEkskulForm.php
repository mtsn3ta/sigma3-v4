<?php

namespace App\Filament\Resources\KehadiranEkskuls\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KehadiranEkskulForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Kehadiran Ekskul')
                    ->schema([

                        Placeholder::make('siswa')
                            ->label('Siswa')
                            ->content(fn($record) => $record?->siswa?->nama),

                        Placeholder::make('ekskul')
                            ->label('Ekstrakurikuler')
                            ->content(
                                fn($record) =>
                                $record?->sesiKegiatan?->ekstrakurikuler?->nama
                            ),

                        Placeholder::make('tanggal')
                            ->label('Tanggal')
                            ->content(
                                fn($record) =>
                                $record?->sesiKegiatan?->tanggal
                            ),

                        Radio::make('status')
                            ->options([
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                                'alpa' => 'Alpa',
                            ])
                            ->required(),

                    ]),

            ]);
    }
}
