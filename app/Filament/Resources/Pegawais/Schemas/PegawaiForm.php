<?php

namespace App\Filament\Resources\Pegawais\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid; // Gunakan Grid untuk kontrol lebih
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PegawaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Menggunakan Section dengan style yang lebih clean
                Section::make('Data Dasar Pegawai')
                    ->description('Informasi identitas utama pegawai.')
                    ->icon('heroicon-o-user-circle')
                    ->columnSpanFull()
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
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('roles', [])),
                        TextInput::make('no_hp')
                            ->label('No. HP')
                            ->tel()
                            ->required(),
                        TextInput::make('rfid_uid')
                            ->label('RFID UID'),
                    ])
                    ->columnSpanFull(),

                Section::make('Hak Akses & Role')
                    ->icon('heroicon-o-shield-check')
                    ->columnSpanFull()
                    ->schema([
                        CheckboxList::make('roles')
                            ->label('Role Tambahan')
                            ->options([
                                'kepala_madrasah' => 'Kepala Madrasah',
                                'waka_kesiswaan' => 'Waka Kesiswaan',
                                'waka_kurikulum' => 'Waka Kurikulum',
                                'waka_humas' => 'Waka Humas',
                                'waka_sarpras' => 'Waka Sarpras',
                                'bk' => 'Guru BK',
                                'piket' => 'Guru Piket',
                            ])
                            ->columnSpanFull()
                            ->helperText('Pilih hak akses sistem yang sesuai dengan jabatan.'),
                    ])
                    ->visible(fn(Get $get) => $get('jenis_pegawai') === 'internal'),

                Section::make('Biodata Lengkap')
                    ->icon('heroicon-o-identification')
                    ->columnSpanFull()
                    ->schema([
                        Radio::make('jenis_kelamin')
                            ->inline()
                            ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                            ->required(),
                        DatePicker::make('tanggal_lahir')
                            ->native(false)
                            ->required(),
                        TextInput::make('tempat_lahir')
                            ->required(),
                        Textarea::make('alamat')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Dokumen & Status')
                    ->icon('heroicon-o-document-duplicate')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('foto')
                                ->image()
                                ->imageEditor()
                                ->directory('pegawai/foto'),
                            FileUpload::make('ttd_digital')
                                ->image()
                                ->directory('pegawai/ttd'),
                        ]),
                        Radio::make('status')
                            ->inline()
                            ->options(['aktif' => 'Aktif', 'nonaktif' => 'Nonaktif'])
                            ->default('aktif'),
                    ]),
            ]);
    }
}
