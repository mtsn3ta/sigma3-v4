<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Siswa')
                    ->tabs([

                        Tab::make('Data Inti')
                            ->schema([
                                TextInput::make('nisn')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Digunakan sebagai username login.'),

                                TextInput::make('nama')
                                    ->required()
                                    ->maxLength(150),

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
                                    ->required(),

                                Radio::make('status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'mutasi' => 'Mutasi',
                                        'lulus' => 'Lulus',
                                    ])
                                    ->default('aktif')
                                    ->required(),
                            ]),

                        Tab::make('Orang Tua')
                            ->schema([
                                TextInput::make('nama_ayah'),
                                TextInput::make('nama_ibu'),

                                TextInput::make('pekerjaan_ayah'),
                                TextInput::make('pekerjaan_ibu'),

                                TextInput::make('nik_ayah'),
                                TextInput::make('nik_ibu'),

                                Textarea::make('alamat_ortu'),

                                TextInput::make('telpon_ortu'),
                            ]),

                        Tab::make('Wali')
                            ->schema([
                                TextInput::make('nama_wali'),

                                TextInput::make('pekerjaan_wali'),

                                Textarea::make('alamat_wali'),

                                TextInput::make('telpon_wali'),
                            ]),

                        Tab::make('Lainnya')
                            ->schema([
                                TextInput::make('no_hp'),

                                TextInput::make('anak_ke')
                                    ->numeric(),

                                TextInput::make('sekolah_asal'),

                                TextInput::make('rfid_uid')
                                    ->unique(ignoreRecord: true),

                                FileUpload::make('foto')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('siswa/foto'),
                            ]),
                    ]),
            ]);
    }
}
