<?php

namespace App\Filament\Resources\Ekstrakurikulers\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EkstrakurikulerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ekstrakurikuler')
                    ->description('Kelola detail dasar dan kapasitas anggota ekstrakurikuler.')
                    ->icon('heroicon-o-shield-check')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Ekstrakurikuler')
                            ->placeholder('Contoh: Pramuka / Basket')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Singkat')
                            ->placeholder('Jelaskan tujuan dan kegiatan utama...')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('kuota')
                            ->label('Kuota Anggota')
                            ->placeholder('Contoh: 30')
                            ->numeric()
                            ->minValue(1)
                            ->columnSpan(1),

                        CheckboxList::make('jadwal')
                            ->label('Jadwal Latihan')
                            ->options([
                                'senin' => 'Senin',
                                'selasa' => 'Selasa',
                                'rabu' => 'Rabu',
                                'kamis' => 'Kamis',
                                'jumat' => 'Jumat',
                                'sabtu' => 'Sabtu',
                            ]),
                        Radio::make('status')
                            ->label('Status Ketersediaan')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->inline()
                            ->default('aktif')
                            ->required(),

                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
