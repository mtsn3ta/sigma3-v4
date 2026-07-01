<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Siswa')
                    ->tabs([
                        Tab::make('Data Inti')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('nisn')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->helperText('Digunakan sebagai username login.'),
                                    TextInput::make('nama')
                                        ->required()
                                        ->maxLength(150),
                                    Radio::make('jenis_kelamin')
                                        ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                                        ->inline()
                                        ->required(),
                                    TextInput::make('tempat_lahir')
                                        ->required(),
                                    DatePicker::make('tanggal_lahir')
                                        ->native(false)
                                        ->required(),
                                    Radio::make('status')
                                        ->options(['aktif' => 'Aktif', 'mutasi' => 'Mutasi', 'lulus' => 'Lulus'])
                                        ->inline()
                                        ->default('aktif')
                                        ->required(),
                                ]),
                                Textarea::make('alamat')
                                    ->rows(3)
                                    ->required(),
                            ]),

                        Tab::make('Orang Tua')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('nama_ayah'),
                                    TextInput::make('nik_ayah'),
                                    TextInput::make('pekerjaan_ayah'),
                                    TextInput::make('nama_ibu'),
                                    TextInput::make('nik_ibu'),
                                    TextInput::make('pekerjaan_ibu'),
                                ]),
                                Textarea::make('alamat_ortu')->rows(2),
                                TextInput::make('telpon_ortu')->tel(),
                            ]),

                        Tab::make('Wali')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('nama_wali'),
                                    TextInput::make('pekerjaan_wali'),
                                    TextInput::make('telpon_wali')->tel(),
                                ]),
                                Textarea::make('alamat_wali')->rows(2),
                            ]),

                        Tab::make('Lainnya')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('no_hp')->tel(),
                                    TextInput::make('anak_ke')->numeric(),
                                    TextInput::make('sekolah_asal'),
                                    TextInput::make('rfid_uid')->unique(ignoreRecord: true),
                                ]),
                                FileUpload::make('foto')
                                    ->image()
                                    ->imageEditor()
                                    ->circleCropper()
                                    ->directory('siswa/foto'),
                            ]),
                    ])
                    ->contained(false)
                    ->columnSpanFull(), // Menghilangkan border tab bawaan agar lebih menyatu dengan "Glass Theme"
            ]);
    }
}
