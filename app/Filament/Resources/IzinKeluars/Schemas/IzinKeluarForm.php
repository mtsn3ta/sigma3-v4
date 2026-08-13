<?php

namespace App\Filament\Resources\IzinKeluars\Schemas;

use App\Support\KelasHelper;
use App\Support\SiswaHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class IzinKeluarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Data Izin Keluar')
                    ->schema([

                        Grid::make(2)->schema([
                            DatePicker::make('tanggal')
                                ->label('Tanggal (Mulai)')
                                ->default(now('Asia/Jakarta')->toDateString())
                                ->required(),

                            DatePicker::make('tanggal_selesai')
                                ->label('Sampai Tanggal (Opsional)')
                                ->placeholder('Pilih jika lebih dari 1 hari')
                                ->minDate(fn(callable $get) => $get('tanggal')) // Tdk boleh sblm tgl mulai
                                ->nullable(),
                        ]),

                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->options(KelasHelper::options())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('siswa_id', null))
                            ->dehydrated(false)
                            ->required(),

                        Select::make('siswa_id')
                            ->label('Siswa (Bisa pilih lebih dari satu untuk Dispensasi)')
                            ->live()
                            ->options(
                                fn($get) => SiswaHelper::byKelas(
                                    $get('kelas_id')
                                )
                            )
                            // PERBAIKAN: Mode multiple hanya aktif di halaman Create (Tambah),
                            // Di halaman Edit kembali jadi single agar tidak error.
                            ->multiple(fn(string $context): bool => $context === 'create')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('jam_keluar')
                            ->label('Jam Keluar')
                            ->type('time')
                            // PERBAIKAN 2: Mengunci jam awal otomatis ke waktu WIB saat ini (01:23)
                            ->default(now('Asia/Jakarta')->format('H:i'))
                            ->required(),

                        TextInput::make('tujuan')
                            ->label('Tujuan')
                            ->required()
                            ->maxLength(150)
                            ->datalist([
                                'Dispensasi',
                                'Puskesmas',
                                'Rumah Sakit',
                                'Apotek',
                                'Rumah',
                                'Lainnya',
                            ]),

                        Textarea::make('keperluan')
                            ->label('Keperluan')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

            ]);
    }
}
