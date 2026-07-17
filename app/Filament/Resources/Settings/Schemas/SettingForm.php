<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('SettingsTabs')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([

                        /*
            |--------------------------------------------------------------------------
            | GENERAL
            |--------------------------------------------------------------------------
            */

                        Tab::make('General')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([

                                Section::make('Informasi Madrasah')
                                    ->icon('heroicon-o-building-library')
                                    ->description('Detail identitas resmi lembaga.')
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
                                    ->description('Tentukan data yang aktif.')
                                    ->schema([

                                        Select::make('kepala_aktif_id')
                                            ->relationship('kepalaAktif', 'nama')
                                            ->searchable()
                                            ->preload(),

                                        Select::make('tahun_ajaran_aktif_id')
                                            ->relationship('tahunAjaranAktif', 'tahun')
                                            ->searchable()
                                            ->preload(),

                                    ]),

                                Section::make('Identitas Visual')
                                    ->icon('heroicon-o-photo')
                                    ->description('Logo dan identitas aplikasi.')
                                    ->schema([

                                        Grid::make(3)
                                            ->schema([

                                                FileUpload::make('logo')
                                                    ->disk('public')
                                                    ->directory('settings')
                                                    ->image()
                                                    ->imageEditor(),

                                                FileUpload::make('logo_resmi')
                                                    ->disk('public')
                                                    ->directory('settings')
                                                    ->image()
                                                    ->imageEditor(),

                                                FileUpload::make('favicon')
                                                    ->disk('public')
                                                    ->directory('settings')
                                                    ->image()
                                                    ->imageEditor(),

                                            ]),

                                        FileUpload::make('kepala_madrasah_photo')
                                            ->disk('public')
                                            ->directory('settings')
                                            ->image()
                                            ->imageEditor()
                                            ->imagePreviewHeight('220'),

                                    ]),

                            ]),

                        /*
            |--------------------------------------------------------------------------
            | RFID
            |--------------------------------------------------------------------------
            */

                        Tab::make('RFID')
                            ->icon('heroicon-o-signal')
                            ->schema([

                                Section::make('RFID Sekolah')
                                    ->description('Pengaturan absensi datang dan pulang.')
                                    ->schema([

                                        Section::make('RFID Sekolah')
                                            ->icon('heroicon-o-building-office-2')
                                            ->description('Pengaturan absensi datang dan pulang siswa.')
                                            ->schema([

                                                Toggle::make('rfid_school_enabled')
                                                    ->label('Aktifkan RFID Sekolah')
                                                    ->default(true)
                                                    ->inline(false),

                                                Grid::make(2)
                                                    ->schema([

                                                        TimePicker::make('rfid_school_checkin_start')
                                                            ->label('Jam Mulai Datang')
                                                            ->seconds(false)
                                                            ->required(),

                                                        TimePicker::make('rfid_school_checkin_end')
                                                            ->label('Jam Selesai Datang')
                                                            ->seconds(false)
                                                            ->required(),

                                                    ]),

                                                Grid::make(2)
                                                    ->schema([

                                                        TimePicker::make('rfid_school_checkout_start')
                                                            ->label('Jam Mulai Pulang')
                                                            ->seconds(false)
                                                            ->required(),

                                                        TimePicker::make('rfid_school_checkout_end')
                                                            ->label('Jam Selesai Pulang')
                                                            ->seconds(false)
                                                            ->required(),

                                                    ]),

                                            ])

                                    ]),

                                Section::make('RFID Ibadah')
                                    ->description('Pengaturan Dhuha dan Dzuhur.')
                                    ->schema([

                                        Section::make('RFID Ibadah')
                                            ->icon('heroicon-o-building-library')
                                            ->description('Pengaturan absensi ibadah Dhuha dan Dzuhur.')
                                            ->schema([

                                                Toggle::make('rfid_ibadah_enabled')
                                                    ->label('Aktifkan RFID Ibadah')
                                                    ->default(true)
                                                    ->inline(false),

                                                Grid::make(2)
                                                    ->schema([

                                                        TimePicker::make('rfid_ibadah_dhuha_start')
                                                            ->label('Jam Mulai Dhuha')
                                                            ->seconds(false)
                                                            ->required(),

                                                        TimePicker::make('rfid_ibadah_dhuha_end')
                                                            ->label('Jam Selesai Dhuha')
                                                            ->seconds(false)
                                                            ->required(),

                                                    ]),

                                                Grid::make(2)
                                                    ->schema([

                                                        TimePicker::make('rfid_ibadah_dzuhur_start')
                                                            ->label('Jam Mulai Dzuhur')
                                                            ->seconds(false)
                                                            ->required(),

                                                        TimePicker::make('rfid_ibadah_dzuhur_end')
                                                            ->label('Jam Selesai Dzuhur')
                                                            ->seconds(false)
                                                            ->required(),

                                                    ]),

                                                Toggle::make('rfid_ibadah_disable_friday')
                                                    ->label('Nonaktifkan Dzuhur Hari Jumat')
                                                    ->helperText(
                                                        'Jika aktif, sistem tidak akan menerima scan Dzuhur pada hari Jumat.'
                                                    ),

                                            ])

                                    ]),

                                Section::make('RFID General')
                                    ->description('Konfigurasi perangkat RFID.')
                                    ->schema([

                                        Section::make('RFID General')
                                            ->icon('heroicon-o-device-phone-mobile')
                                            ->description('Pengaturan perangkat RFID dan NFC.')
                                            ->schema([

                                                Toggle::make('rfid_general_enable_usb_reader')
                                                    ->label('Aktifkan USB RFID Reader')
                                                    ->default(true),

                                                Toggle::make('rfid_general_enable_web_nfc')
                                                    ->label('Aktifkan Web NFC (Android)')
                                                    ->default(true),

                                                TextInput::make('rfid_general_uid_lock_seconds')
                                                    ->label('UID Lock (Detik)')
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->maxValue(10)
                                                    ->suffix('detik')
                                                    ->helperText(
                                                        'Mencegah UID yang sama diproses berulang dalam waktu singkat.'
                                                    ),

                                            ])

                                    ]),

                            ]),

                    ])

            ]);
    }
}
