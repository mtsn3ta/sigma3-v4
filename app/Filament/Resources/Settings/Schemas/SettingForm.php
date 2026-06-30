<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Madrasah')
                    ->schema([

                        TextInput::make('nama_madrasah')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('alamat')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('telepon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(150),

                    ])
                    ->columns(2),
                Section::make('Sistem')
                    ->schema([

                        Select::make('kepala_aktif_id')
                            ->label('Kepala Madrasah')
                            ->relationship(
                                'kepalaAktif',
                                'nama'
                            )
                            ->searchable()
                            ->preload(),

                        Select::make('tahun_ajaran_aktif_id')
                            ->label('Tahun Ajaran Aktif')
                            ->relationship(
                                'tahunAjaranAktif',
                                'tahun'
                            )
                            ->searchable()
                            ->preload(),

                    ])
                    ->columns(2),
                Section::make('Identitas')
                    ->schema([

                        FileUpload::make('logo')
                            ->label('Logo Aplikasi')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->imageEditor(),

                        FileUpload::make('logo_resmi')
                            ->label('Logo Dokumen Resmi')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->imageEditor(),

                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->imageEditor(),

                    ])

            ]);
    }
}
