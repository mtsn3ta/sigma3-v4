<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Madrasah')
                    ->icon('heroicon-o-building-library')
                    ->description('Detail identitas resmi lembaga.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('nama_madrasah')
                            ->label('Nama Madrasah')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('alamat')
                            ->rows(2)
                            ->columnSpanFull(),

                        TextInput::make('telepon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(150),
                    ]),

                Section::make('Pengaturan Sistem Aktif')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->description('Tentukan data yang aktif untuk operasional aplikasi.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('kepala_aktif_id')
                            ->label('Kepala Madrasah Saat Ini')
                            ->relationship('kepalaAktif', 'nama')
                            ->searchable()
                            ->preload(),

                        Select::make('tahun_ajaran_aktif_id')
                            ->label('Tahun Ajaran Aktif')
                            ->relationship('tahunAjaranAktif', 'tahun')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Identitas Visual & Media')
                    ->icon('heroicon-o-photo')
                    ->description('Kelola aset visual untuk dokumen resmi dan tampilan aplikasi.')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)->schema([
                            FileUpload::make('logo')
                                ->label('Logo Aplikasi')
                                ->disk('public')
                                ->directory('settings')
                                ->image()
                                ->imageEditor(),

                            FileUpload::make('logo_resmi')
                                ->label('Logo Dokumen')
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
                        ]),

                        FileUpload::make('kepala_madrasah_photo')
                            ->label('Foto Kepala Madrasah')
                            ->disk('public')
                            ->directory('settings')
                            ->image()
                            ->imageEditor()
                            ->imagePreviewHeight('220'),
                    ]),
            ]);
    }
}
