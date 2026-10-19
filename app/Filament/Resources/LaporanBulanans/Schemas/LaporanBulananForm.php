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
                    ->icon('heroicon-o-information-circle')
                    ->description('Detail periode laporan bulanan ekstrakurikuler')
                    ->schema([
                        Select::make('ekstrakurikuler_id')
                            ->relationship('ekstrakurikuler', 'nama')
                            ->label('Ekstrakurikuler')
                            ->prefixIcon('heroicon-o-academic-cap')
                            ->disabled(),

                        Select::make('bulan')
                            ->label('Bulan')
                            ->prefixIcon('heroicon-o-calendar')
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
                            ->label('Tahun')
                            ->prefixIcon('heroicon-o-calendar-days')
                            ->disabled(),

                        TextInput::make('status')
                            ->label('Status')
                            ->prefixIcon('heroicon-o-check-badge')
                            ->formatStateUsing(fn($state) => ucfirst(str_replace('_', ' ', $state)))
                            ->disabled(),
                    ])
                    ->columns(['default' => 1, 'sm' => 2, 'md' => 4])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB I
                |--------------------------------------------------------------------------
                */
                Section::make('BAB I — Pendahuluan')
                    ->icon('heroicon-o-book-open')
                    ->description('Latar belakang dan tujuan penyusunan laporan bulanan')
                    ->collapsible()
                    ->schema([
                        RichEditor::make('pendahuluan')
                            ->label('Pendahuluan')
                            ->placeholder('Uraikan latar belakang pelaksanaan pembinaan ekstrakurikuler pada bulan ini...')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('tujuan')
                            ->label('Maksud dan Tujuan')
                            ->placeholder('Tuliskan tujuan dan sasaran capaian program bulanan...')
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
                    ->icon('heroicon-o-calendar-days')
                    ->description('Rekapitulasi kehadiran anggota, agenda program latihan, dan statistik bulanan')
                    ->collapsible()
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
                    ->icon('heroicon-o-trophy')
                    ->description('Daftar proposal, realisasi kegiatan, dan capaian prestasi selama satu bulan')
                    ->collapsible()
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
                    ->icon('heroicon-o-clipboard-document-check')
                    ->description('Analisis kendala dan tindak lanjut dari program yang telah berjalan')
                    ->collapsible()
                    ->schema([
                        RichEditor::make('evaluasi')
                            ->label('Evaluasi Program')
                            ->placeholder('Uraikan evaluasi umum jalannya latihan/pembinaan...')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('kendala')
                            ->label('Kendala di Lapangan')
                            ->placeholder('Sebutkan kendala teknis, sarana, atau kehadiran yang dihadapi...')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('solusi')
                            ->label('Solusi & Tindak Lanjut')
                            ->placeholder('Jelaskan langkah penyelesaian dan rencana perbaikan ke depan...')
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
                    ->icon('heroicon-o-check-circle')
                    ->description('Kesimpulan dan harapan terhadap perkembangan kegiatan ekstrakurikuler')
                    ->collapsible()
                    ->schema([
                        RichEditor::make('penutup')
                            ->label('Penutup')
                            ->placeholder('Tuliskan kesimpulan akhir dan ucapan terima kasih kepada pihak madrasah...')
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
                    ->icon('heroicon-o-paper-clip')
                    ->description('Upload foto atau dokumen pendukung kegiatan bulanan')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('dokumentasi')
                            ->label('Dokumentasi Kegiatan Bulanan')
                            ->helperText('Unggah foto-foto kegiatan atau latihan (Maks. 5MB per file, format JPG/PNG)')
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
