<?php

namespace App\Filament\Resources\EkstrakurikulerPengampus\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\EkstrakurikulerPengampu;

class EkstrakurikulerPengampuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Pengampu')
                    ->schema([

                        Select::make('ekstrakurikuler_id')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('pegawai_id')
                            ->relationship('pegawai', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Radio::make('role')
                            ->options([
                                'pembina' => 'Pembina',
                                'pelatih' => 'Pelatih',
                            ])
                            ->required()
                            ->rule(function ($get, $record) {

                                return function ($attribute, $value, $fail) use ($get, $record) {

                                    $query = EkstrakurikulerPengampu::query()
                                        ->where('ekstrakurikuler_id', $get('ekstrakurikuler_id'))
                                        ->where('pegawai_id', $get('pegawai_id'))
                                        ->where('role', $value);

                                    if ($record) {
                                        $query->whereKeyNot($record->id);
                                    }

                                    if ($query->exists()) {
                                        $fail(
                                            'Pegawai sudah terdaftar dengan role tersebut pada ekstrakurikuler ini.'
                                        );
                                    }
                                };
                            }),

                    ])
                    ->columns(2),

            ]);
    }
}
