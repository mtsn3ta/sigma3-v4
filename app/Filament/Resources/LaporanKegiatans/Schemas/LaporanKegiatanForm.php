<?php

namespace App\Filament\Resources\LaporanKegiatans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

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
                Section::make('BAB I — Pendahuluan')
                    ->description('Informasi dasar dari proposal kegiatan yang dilaporkan')
                    ->schema([
                        Placeholder::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->content(fn($record) => $record?->proposal?->nama_kegiatan ?? '-'),

                        Placeholder::make('jenis_kegiatan')
                            ->label('Jenis Kegiatan')
                            ->content(fn($record) => $record?->proposal?->jenis_kegiatan_label ?? '-'),

                        Placeholder::make('ekstrakurikuler')
                            ->label('Ekstrakurikuler')
                            ->content(fn($record) => $record?->proposal?->ekstrakurikuler?->nama ?? '-'),

                        Placeholder::make('tempat')
                            ->label('Tempat')
                            ->content(fn($record) => $record?->proposal?->tempat ?? '-'),

                        Placeholder::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->content(fn($record) => $record?->proposal?->tanggal_mulai?->format('d M Y') ?? '-'),

                        Placeholder::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->content(fn($record) => $record?->proposal?->tanggal_selesai?->format('d M Y') ?? '-'),

                        Placeholder::make('latar_belakang')
                            ->label('Latar Belakang')
                            ->content(fn($record) => new HtmlString(
                                $record?->proposal?->latar_belakang ?? '-'
                            ))
                            ->columnSpanFull(),

                        Placeholder::make('tujuan')
                            ->label('Tujuan')
                            ->content(fn($record) => new HtmlString(
                                $record?->proposal?->tujuan ?? '-'
                            ))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB II
                |--------------------------------------------------------------------------
                */
                Section::make('BAB II — Pelaksanaan Kegiatan')
                    ->schema([
                        Placeholder::make('peserta')
                            ->label('Peserta')
                            ->content(fn($record) => $record ? $record->peserta()->count() . ' Orang' : '-'),

                        Placeholder::make('pendamping')
                            ->label('Pendamping')
                            ->content(fn($record) => $record ? $record->pendamping()->count() . ' Orang' : '-'),

                        Placeholder::make('realisasi')
                            ->label('Item Anggaran')
                            ->content(fn($record) => $record ? $record->realisasiAnggaran()->count() . ' Item' : '-'),

                        RichEditor::make('uraian_pelaksanaan')
                            ->label('Uraian Pelaksanaan')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(3) // Membagi 3 placeholder di atas menjadi sejajar
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB III
                |--------------------------------------------------------------------------
                */
                Section::make('BAB III — Hasil Kegiatan')
                    ->schema([
                        RichEditor::make('pencapaian')
                            ->label('Pencapaian')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('prestasi')
                            ->label('Prestasi')
                            ->columnSpanFull(),

                        RichEditor::make('evaluasi')
                            ->label('Evaluasi')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB IV
                |--------------------------------------------------------------------------
                */
                Section::make('BAB IV — Realisasi Anggaran')
                    ->schema([
                        Placeholder::make('info')
                            ->label('')
                            ->content('Realisasi anggaran dikelola melalui menu Relation Manager "Realisasi Anggaran" di bagian bawah halaman ini.')
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
                    ->description('Upload bukti fisik dari pelaksanaan kegiatan')
                    ->schema([
                        FileUpload::make('dokumentasi')
                            ->label('Dokumentasi (Foto/Video)')
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
                            ->label('Sertifikat / Piagam')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/sertifikat')
                            ->disk('public'),

                        FileUpload::make('berita_acara')
                            ->label('Berita Acara')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/berita-acara')
                            ->disk('public'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

            ]);
    }
}
