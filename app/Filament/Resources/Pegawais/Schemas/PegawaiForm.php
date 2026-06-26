<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Dasar')
                    ->schema([

                        TextInput::make('nama')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('nik_nip')
                            ->label('NIK / NIP')
                            ->helperText('Digunakan sebagai username login.')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Select::make('jenis_pegawai')
                            ->options([
                                'internal' => 'Internal',
                                'eksternal' => 'Eksternal',
                            ])
                            ->required(),

                        TextInput::make('no_hp')
                            ->tel()
                            ->required(),

                        TextInput::make('rfid_uid')
                            ->label('RFID UID')
                            ->nullable()
                            ->unique(ignoreRecord: true),

                    ])
                    ->columns(2),

                Section::make('Biodata')
                    ->schema([

                        Radio::make('jenis_kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),

                        TextInput::make('tempat_lahir')
                            ->required(),

                        DatePicker::make('tanggal_lahir')
                            ->native(false)
                            ->required(),

                        Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),

                    ])
                    ->columns(3),

                Section::make('Dokumen')
                    ->schema([

                        FileUpload::make('foto')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('pegawai/foto')
                            ->downloadable()
                            ->openable(),

                        FileUpload::make('ttd_digital')
                            ->image()
                            ->disk('public')
                            ->directory('pegawai/ttd')
                            ->downloadable()
                            ->openable(),

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
