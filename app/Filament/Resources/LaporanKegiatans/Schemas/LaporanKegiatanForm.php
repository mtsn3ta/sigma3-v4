<?php

namespace App\Filament\Resources\LaporanKegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LaporanKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                |--------------------------------------------------------------------------
                | BAB I
                |--------------------------------------------------------------------------
                */

                Section::make('BAB I - Pendahuluan')

                    ->schema([

                        TextInput::make('proposal.nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->disabled(),

                        TextInput::make('proposal.jenis_kegiatan')
                            ->label('Jenis Kegiatan')
                            ->disabled(),

                        TextInput::make('proposal.ekstrakurikuler.nama')
                            ->label('Ekstrakurikuler')
                            ->disabled(),

                        Grid::make(2)

                            ->schema([

                                DatePicker::make('proposal.tanggal_mulai')
                                    ->label('Tanggal Mulai')
                                    ->disabled(),

                                DatePicker::make('proposal.tanggal_selesai')
                                    ->label('Tanggal Selesai')
                                    ->disabled(),

                            ]),

                        TextInput::make('proposal.tempat')
                            ->disabled(),

                        RichEditor::make('proposal.latar_belakang')
                            ->label('Latar Belakang')
                            ->disabled()
                            ->toolbarButtons([]),

                        RichEditor::make('proposal.tujuan')
                            ->disabled()
                            ->toolbarButtons([]),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | BAB II
                |--------------------------------------------------------------------------
                */

                Section::make('BAB II - Pelaksanaan Kegiatan')

                    ->schema([

                        Grid::make(3)

                            ->schema([

                                Placeholder::make('peserta')

                                    ->label('Peserta')

                                    ->content(
                                        fn($record) =>

                                        $record
                                            ? $record->peserta()->count() . ' Orang'
                                            : '-'
                                    ),

                                Placeholder::make('pendamping')

                                    ->label('Pendamping')

                                    ->content(
                                        fn($record) =>

                                        $record
                                            ? $record->pendamping()->count() . ' Orang'
                                            : '-'
                                    ),

                                Placeholder::make('realisasi')

                                    ->label('Item Anggaran')

                                    ->content(
                                        fn($record) =>

                                        $record
                                            ? $record->realisasiAnggaran()->count() . ' Item'
                                            : '-'
                                    ),

                            ]),

                        RichEditor::make('uraian_pelaksanaan')

                            ->label('Uraian Pelaksanaan')

                            ->required()

                            ->columnSpanFull(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | BAB III
                |--------------------------------------------------------------------------
                */

                Section::make('BAB III - Hasil Kegiatan')

                    ->schema([

                        RichEditor::make('pencapaian')

                            ->required(),

                        RichEditor::make('prestasi')

                            ->helperText(
                                'Kosongkan apabila kegiatan tidak menghasilkan prestasi.'
                            ),

                        RichEditor::make('evaluasi')

                            ->required(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | BAB IV
                |--------------------------------------------------------------------------
                */

                Section::make('BAB IV - Realisasi Anggaran')

                    ->schema([

                        Placeholder::make('info')

                            ->label('')

                            ->content(
                                'Realisasi anggaran dikelola melalui Relation Manager "Realisasi Anggaran".'
                            ),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | BAB V
                |--------------------------------------------------------------------------
                */

                Section::make('BAB V - Penutup')

                    ->schema([

                        RichEditor::make('penutup')

                            ->required(),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | Lampiran
                |--------------------------------------------------------------------------
                */

                Section::make('Lampiran')

                    ->schema([

                        FileUpload::make('dokumentasi')
                            ->label('Dokumentasi')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->reorderable()
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/dokumentasi')
                            ->disk('public')
                            ->columnSpanFull(),

                        FileUpload::make('sertifikat')
                            ->label('Sertifikat')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/sertifikat')
                            ->disk('public'),

                        FileUpload::make('berita_acara')
                            ->label('Berita Acara')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/berita-acara')
                            ->disk('public'),

                    ])
                    ->columns(2),

            ]);
    }
}
