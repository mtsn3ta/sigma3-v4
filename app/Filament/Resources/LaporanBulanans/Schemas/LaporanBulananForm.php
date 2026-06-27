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
                Section::make('Informasi Laporan')

                    ->schema([

                        Select::make('ekstrakurikuler_id')
                            ->relationship(
                                'ekstrakurikuler',
                                'nama'
                            )
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
                            ->formatStateUsing(
                                fn($state) => ucfirst(str_replace('_', ' ', $state))
                            )
                            ->disabled(),

                    ])
                    ->columns(4),
                Section::make('BAB I - Pendahuluan')

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
                Section::make('BAB II - Program Pembinaan')

                    ->schema([

                        View::make(
                            'filament.laporan-bulanan.rekap-kehadiran'
                        ),

                        View::make(
                            'filament.laporan-bulanan.program-latihan'
                        ),

                        View::make(
                            'filament.laporan-bulanan.statistik'
                        ),

                    ])

                    ->columnSpanFull(),
                Section::make('BAB III - Kegiatan dan Prestasi')

                    ->schema([

                        View::make(
                            'filament.laporan-bulanan.proposal'
                        ),

                        View::make(
                            'filament.laporan-bulanan.laporan-kegiatan'
                        ),

                        View::make(
                            'filament.laporan-bulanan.prestasi'
                        ),

                    ])

                    ->columnSpanFull(),
                Section::make('BAB IV - Evaluasi')

                    ->schema([

                        RichEditor::make('evaluasi')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('kendala')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('solusi')
                            ->required()
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),
                Section::make('BAB V - Penutup')

                    ->schema([

                        RichEditor::make('penutup')
                            ->required()
                            ->columnSpanFull(),

                    ]),
                Section::make('Lampiran')

                    ->schema([

                        FileUpload::make('dokumentasi')

                            ->label('Dokumentasi Bulanan')

                            ->disk('public')

                            ->directory('laporan-bulanan/dokumentasi')

                            ->multiple()

                            ->image()

                            ->downloadable()

                            ->openable()

                            ->reorderable(),

                    ]),
            ]);
    }
}
