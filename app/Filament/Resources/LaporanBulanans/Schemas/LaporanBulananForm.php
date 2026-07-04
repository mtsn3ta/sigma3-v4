<?php

namespace App\Filament\Resources\LaporanBulanans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Section;

class LaporanBulananForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | Informasi Dasar
                |--------------------------------------------------------------------------
                */
                Section::make('Informasi Laporan')
                    ->description('Detail periode laporan bulanan ekstrakurikuler')
                    ->schema([
                        Select::make('ekstrakurikuler_id')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->label('Ekstrakurikuler')
                            ->disabled(),

                        Select::make('bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->disabled(),

                        TextInput::make('tahun')
                            ->disabled(),

                        TextInput::make('status')
                            ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state)))
                            ->disabled(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB I
                |--------------------------------------------------------------------------
                */
                Section::make('BAB I — Pendahuluan')
                    ->description('Latar belakang dan tujuan penyusunan laporan bulanan')
                    ->schema([
                        RichEditor::make('pendahuluan')
                            ->label('Pendahuluan')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('tujuan')
                            ->label('Maksud dan Tujuan')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB II
                |--------------------------------------------------------------------------
                */
                Section::make('BAB II — Program Pembinaan')
                    ->description('Rekapitulasi kehadiran, program latihan, dan statistik bulanan')
                    ->schema([
                        View::make('filament.laporan-bulanan.rekap-kehadiran'),
                        View::make('filament.laporan-bulanan.program-latihan'),
                        View::make('filament.laporan-bulanan.statistik'),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB III
                |--------------------------------------------------------------------------
                */
                Section::make('BAB III — Kegiatan dan Prestasi')
                    ->description('Daftar proposal, realisasi kegiatan, dan capaian prestasi selama satu bulan')
                    ->schema([
                        View::make('filament.laporan-bulanan.proposal'),
                        View::make('filament.laporan-bulanan.laporan-kegiatan'),
                        View::make('filament.laporan-bulanan.prestasi'),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB IV
                |--------------------------------------------------------------------------
                */
                Section::make('BAB IV — Evaluasi')
                    ->description('Analisis kendala dan tindak lanjut dari program yang telah berjalan')
                    ->schema([
                        RichEditor::make('evaluasi')
                            ->label('Evaluasi Program')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('kendala')
                            ->label('Kendala di Lapangan')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('solusi')
                            ->label('Solusi & Tindak Lanjut')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB V
                |--------------------------------------------------------------------------
                */
                Section::make('BAB V — Penutup')
                    ->schema([
                        RichEditor::make('penutup')
                            ->label('Penutup')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | Lampiran
                |--------------------------------------------------------------------------
                */
                Section::make('Lampiran Dokumen')
                    ->description('Upload foto atau dokumen pendukung kegiatan bulanan')
                    ->schema([
                        FileUpload::make('dokumentasi')
                            ->label('Dokumentasi Bulanan')
                            ->disk('public')
                            ->directory('laporan-bulanan/dokumentasi')
                            ->multiple()
                            ->image()
                            ->downloadable()
                            ->openable()
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
