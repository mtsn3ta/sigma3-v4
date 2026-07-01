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
                Section::make('Penugasan Pengampu')
                    ->description('Tentukan ekstrakurikuler, pegawai yang bertanggung jawab, serta peran mereka.')
                    ->icon('heroicon-o-user-plus')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('ekstrakurikuler_id')
                            ->label('Ekstrakurikuler')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(), // Diletakkan di atas sendiri agar fokus utama jelas

                        Select::make('pegawai_id')
                            ->label('Pegawai / Guru')
                            ->relationship('pegawai', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        CheckboxList::make('role') // Disesuaikan: biasanya 'role' (singular) di model
                            ->label('Jabatan / Role')
                            ->options([
                                'pembina' => 'Pembina',
                                'pelatih' => 'Pelatih',
                            ])
                            ->columns(2)
                            ->gridDirection('row') // Agar pilihan tampil rapi menyamping
                            ->required(),
                    ]),
            ]);
    }
}
