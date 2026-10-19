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
                    ->icon('heroicon-o-book-open')
                    ->description('Informasi dasar dari proposal kegiatan yang dilaporkan')
                    ->collapsible()
                    ->schema([
                        Placeholder::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->content(fn($record) => new HtmlString('<strong>' . ($record?->proposal?->nama_kegiatan ?? '-') . '</strong>')),

                        Placeholder::make('ekstrakurikuler')
                            ->label('Ekstrakurikuler')
                            ->content(fn($record) => $record?->proposal?->ekstrakurikuler?->nama ?? '-'),

                        Placeholder::make('jenis_kegiatan')
                            ->label('Jenis Kegiatan')
                            ->content(fn($record) => $record?->proposal?->jenis_kegiatan_label ?? '-'),

                        Placeholder::make('tempat')
                            ->label('Tempat / Lokasi')
                            ->content(fn($record) => $record?->proposal?->tempat ?? '-'),

                        Placeholder::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->content(fn($record) => $record?->proposal?->tanggal_mulai?->translatedFormat('d F Y') ?? '-'),

                        Placeholder::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->content(fn($record) => $record?->proposal?->tanggal_selesai?->translatedFormat('d F Y') ?? '-'),

                        Placeholder::make('latar_belakang')
                            ->label('Latar Belakang (dari Proposal)')
                            ->content(fn($record) => new HtmlString(
                                '<div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0; font-size:0.875rem;">' .
                                ($record?->proposal?->latar_belakang ?: '<span style="color:#94a3b8; font-style:italic;">Tidak ada data latar belakang</span>') .
                                '</div>'
                            ))
                            ->columnSpanFull(),

                        Placeholder::make('tujuan')
                            ->label('Tujuan Kegiatan (dari Proposal)')
                            ->content(fn($record) => new HtmlString(
                                '<div style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0; font-size:0.875rem;">' .
                                ($record?->proposal?->tujuan ?: '<span style="color:#94a3b8; font-style:italic;">Tidak ada data tujuan</span>') .
                                '</div>'
                            ))
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'sm' => 2, 'md' => 3])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB II
                |--------------------------------------------------------------------------
                */
                Section::make('BAB II — Pelaksanaan Kegiatan')
                    ->icon('heroicon-o-calendar-days')
                    ->description('Rincian pelaksanaan teknis, peserta, dan pendamping kegiatan')
                    ->collapsible()
                    ->schema([
                        Placeholder::make('peserta')
                            ->label('Peserta Siswa')
                            ->content(fn($record) => new HtmlString(
                                '<div style="background:#ecfdf5; color:#047857; font-weight:700; padding:8px 14px; border-radius:8px; border:1px solid #a7f3d0; text-align:center;">' .
                                ($record ? $record->peserta()->count() . ' Siswa Terdaftar' : '-') .
                                '</div>'
                            )),

                        Placeholder::make('pendamping')
                            ->label('Guru & Pendamping')
                            ->content(fn($record) => new HtmlString(
                                '<div style="background:#eff6ff; color:#1d4ed8; font-weight:700; padding:8px 14px; border-radius:8px; border:1px solid #bfdbfe; text-align:center;">' .
                                ($record ? $record->pendamping()->count() . ' Orang Pendamping' : '-') .
                                '</div>'
                            )),

                        Placeholder::make('realisasi')
                            ->label('Item Realisasi Anggaran')
                            ->content(fn($record) => new HtmlString(
                                '<div style="background:#fefce8; color:#a16207; font-weight:700; padding:8px 14px; border-radius:8px; border:1px solid #fef08a; text-align:center;">' .
                                ($record ? $record->realisasiAnggaran()->count() . ' Item Anggaran' : '-') .
                                '</div>'
                            )),

                        RichEditor::make('uraian_pelaksanaan')
                            ->label('Uraian Pelaksanaan Kegiatan')
                            ->placeholder('Jelaskan secara kronologis jalannya kegiatan, susunan acara, dan dinamika di lapangan...')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(['default' => 1, 'sm' => 3])
                    ->columnSpanFull(),

                /*
                |--------------------------------------------------------------------------
                | BAB III
                |--------------------------------------------------------------------------
                */
                Section::make('BAB III — Hasil Kegiatan & Prestasi')
                    ->icon('heroicon-o-trophy')
                    ->description('Capaian target, perolehan kejuaraan/prestasi, dan evaluasi kegiatan')
                    ->collapsible()
                    ->schema([
                        RichEditor::make('pencapaian')
                            ->label('Pencapaian / Capaian Hasil')
                            ->placeholder('Uraikan capaian positif dan target yang berhasil diraih selama kegiatan...')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('prestasi')
                            ->label('Prestasi / Penghargaan yang Diraih')
                            ->placeholder('Tuliskan perolehan juara, medali, atau piagam penghargaan jika ada...')
                            ->columnSpanFull(),

                        RichEditor::make('evaluasi')
                            ->label('Evaluasi Pelaksanaan')
                            ->placeholder('Tuliskan catatan evaluasi, kekurangan, dan pembelajaran untuk kegiatan mendatang...')
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
                    ->icon('heroicon-o-banknotes')
                    ->description('Rincian pengeluaran dana dan bukti nota pembayaran')
                    ->collapsible()
                    ->schema([
                        Placeholder::make('info')
                            ->label('')
                            ->content(new HtmlString(
                                '<div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; font-size:0.875rem; display:flex; align-items:center; gap:10px;">' .
                                '<svg style="width:20px; height:20px; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' .
                                '<span>Realisasi anggaran dan upload bukti nota dikelola melalui tabel <strong>"Realisasi Anggaran"</strong> di bagian bawah halaman ini.</span>' .
                                '</div>'
                            ))
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
                    ->description('Kesimpulan dan ucapan terima kasih')
                    ->collapsible()
                    ->schema([
                        RichEditor::make('penutup')
                            ->label('Penutup')
                            ->placeholder('Tuliskan kesimpulan akhir dan ucapan terima kasih kepada madrasah...')
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
                    ->description('Upload foto dokumentasi kegiatan, sertifikat/piagam, dan berita acara')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('dokumentasi')
                            ->label('Dokumentasi Kegiatan (Foto/Flyer)')
                            ->helperText('Unggah foto-foto pelaksanaan kegiatan (Format JPG/PNG, Maks. 5MB per file)')
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
                            ->label('Sertifikat / Piagam Kejuaraan')
                            ->helperText('Unggah berkas sertifikat/piagam jika ada (PDF/Gambar)')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/sertifikat')
                            ->disk('public'),

                        FileUpload::make('berita_acara')
                            ->label('Berita Acara / Laporan Hasil Lomba')
                            ->helperText('Unggah berita acara resmi atau bukti kejuaraan (PDF/Gambar)')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->downloadable()
                            ->openable()
                            ->directory('laporan-kegiatan/berita-acara')
                            ->disk('public'),
                    ])
                    ->columns(['default' => 1, 'sm' => 2])
                    ->columnSpanFull(),

            ]);
    }
}
