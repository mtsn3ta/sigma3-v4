<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Schemas;

use App\Models\PlotingGuru;
use App\Models\TahunAjaran;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PerangkatPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Perangkat Pembelajaran')
                    ->description('Unggah dokumen perangkat ajar seperti Modul Ajar, RPP, Silabus, Prota, atau Promes.')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->schema([
                        Select::make('pegawai_id')
                            ->label('Guru Pengampu')
                            ->relationship('pegawai', 'nama')
                            ->hiddenOn('create')
                            ->required(),

                        Grid::make(2)->schema([
                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->relationship('tahunAjaran', 'tahun')
                                ->default(fn () => TahunAjaran::aktifId())
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('kelas_id')
                                ->label('Kelas Sasaran (Opsional)')
                                ->relationship('kelas', 'nama_kelas')
                                ->searchable()
                                ->preload()
                                ->placeholder('Semua Kelas yang Diajar')
                                ->helperText('Kosongkan jika perangkat berlaku umum untuk semua kelas.'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('mapel')
                                ->label('Mata Pelajaran')
                                ->placeholder('Pilih atau ketik nama mapel')
                                ->datalist(fn () => PlotingGuru::where('pegawai_id', Auth::user()?->pegawai?->id)
                                    ->whereNotNull('mapel')
                                    ->distinct()
                                    ->pluck('mapel')
                                    ->toArray()
                                )
                                ->required(),

                            Select::make('jenis')
                                ->label('Jenis Perangkat')
                                ->options([
                                    'Modul Ajar' => 'Modul Ajar (Kurikulum Merdeka)',
                                    'RPP'        => 'RPP (Rencana Pelaksanaan Pembelajaran)',
                                    'Silabus'    => 'Silabus / ATP',
                                    'Prota'      => 'Prota (Program Tahunan)',
                                    'Prosem'     => 'Promes (Program Semester)',
                                    'Bahan Ajar' => 'Bahan Ajar / LKPD',
                                    'Lainnya'    => 'Lainnya',
                                ])
                                ->default('Modul Ajar')
                                ->native(false)
                                ->required(),
                        ]),

                        TextInput::make('judul')
                            ->label('Judul Perangkat')
                            ->placeholder('Contoh: Modul Ajar Bab 1 — Teks Laporan Hasil Observasi')
                            ->required()
                            ->columnSpanFull(),

                        FileUpload::make('file_path')
                            ->label('File Dokumen')
                            ->helperText('Format yang didukung: PDF, DOCX, DOC, XLSX, XLS, PPTX, PPT, ZIP, RAR (Maks. 25 MB).')
                            ->disk('public')
                            ->directory('perangkat-pembelajaran')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-powerpoint',
                                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                'application/zip',
                                'application/x-rar-compressed',
                                'application/octet-stream',
                            ])
                            ->maxSize(25600)
                            ->downloadable()
                            ->openable()
                            ->required()
                            ->columnSpanFull(),

                        Textarea::make('deskripsi')
                            ->label('Catatan / Keterangan Tambahan (Opsional)')
                            ->rows(3)
                            ->placeholder('Tambahkan catatan materi pokok, kompetensi, atau tujuan pembelajaran bila diperlukan...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
