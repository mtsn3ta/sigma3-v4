<?php

namespace App\Filament\Resources\ProposalKegiatans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Ekstrakurikuler;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Support\ProposalTemplate;

class ProposalKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->disabled(fn($record) => $record?->isLocked() ?? false)
            ->components([

                Section::make('Informasi Kegiatan')
                    ->icon('heroicon-o-calendar-days')
                    ->description('Detail umum kegiatan ekstrakurikuler yang akan dilaksanakan')
                    ->schema([

                        Select::make('ekstrakurikuler_id')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->label('Ekstrakurikuler')
                            ->prefixIcon('heroicon-o-academic-cap')
                            ->options(function () {

                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return [];
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {
                                    return Ekstrakurikuler::pluck('nama', 'id');
                                }

                                return Ekstrakurikuler::query()
                                    ->whereIn(
                                        'id',
                                        $user->pegawai
                                            ? $user->pegawai
                                            ->pengampus()
                                            ->pluck('ekstrakurikuler_id')
                                            : []
                                    )
                                    ->pluck(
                                        'nama',
                                        'id'
                                    );
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(['default' => 1, 'lg' => 1]),

                        TextInput::make('nama_kegiatan')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->label('Nama Kegiatan')
                            ->placeholder('Contoh: Kejuaraan Karate Terbuka Piala Bupati 2026')
                            ->prefixIcon('heroicon-o-document-text')
                            ->required()
                            ->maxLength(150)
                            ->columnSpan(['default' => 1, 'lg' => 2]),

                        Select::make('jenis_kegiatan')
                            ->label('Jenis Kegiatan')
                            ->prefixIcon('heroicon-o-tag')
                            ->options([
                                'lomba' => 'Lomba / Kompetisi',
                                'festival' => 'Festival / Pentas Seni',
                                'kemah' => 'Kemah / Perkemahan',
                                'pelatihan' => 'Pelatihan / Diklat',
                                'seminar' => 'Seminar',
                                'workshop' => 'Workshop / Lokakarya',
                                'studi_banding' => 'Studi Banding',
                                'kunjungan' => 'Kunjungan Edukasi',
                                'bakti_sosial' => 'Bakti Sosial',
                                'lainnya' => 'Lainnya',
                            ])
                            ->searchable()
                            ->required(),

                        TextInput::make('nama_penyelenggara')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->label('Penyelenggara')
                            ->placeholder('Contoh: Kemenag Tulungagung / FORKI Jatim')
                            ->prefixIcon('heroicon-o-building-office-2')
                            ->required()
                            ->maxLength(150),

                        Select::make('tingkat_kegiatan')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->label('Tingkat Kegiatan')
                            ->prefixIcon('heroicon-o-trophy')
                            ->options([
                                'kecamatan' => 'Kecamatan',
                                'kabupaten' => 'Kabupaten / Kota',
                                'karesidenan' => 'Karesidenan',
                                'provinsi' => 'Provinsi',
                                'nasional' => 'Nasional',
                                'internasional' => 'Internasional',
                            ])
                            ->required(),

                        TextInput::make('tempat')
                            ->label('Tempat / Lokasi')
                            ->placeholder('Contoh: GOR Lembu Peteng Tulungagung')
                            ->prefixIcon('heroicon-o-map-pin')
                            ->required()
                            ->maxLength(150)
                            ->columnSpan(['default' => 1, 'sm' => 2, 'lg' => 3]),

                        DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->prefixIcon('heroicon-o-calendar')
                            ->native(false)
                            ->required(),

                        DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->prefixIcon('heroicon-o-calendar')
                            ->native(false)
                            ->afterOrEqual('tanggal_mulai')
                            ->required(),
                    ])
                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 3])
                    ->columnSpanFull(),

                Section::make('Dokumen Pendukung')
                    ->icon('heroicon-o-paper-clip')
                    ->description('Surat undangan resmi dan brosur/flyer terkait kegiatan')
                    ->collapsible()
                    ->schema([

                        TextInput::make('nomor_surat_undangan')
                            ->label('Nomor Surat Undangan')
                            ->placeholder('Contoh: 120/PAN-PEL/VIII/2026')
                            ->prefixIcon('heroicon-o-envelope'),

                        DatePicker::make('tanggal_surat_undangan')
                            ->label('Tanggal Surat Undangan')
                            ->prefixIcon('heroicon-o-calendar')
                            ->native(false),

                        FileUpload::make('surat_undangan')
                            ->label('Upload Surat Undangan')
                            ->helperText('Format PDF, JPG, atau PNG (Maks. 5MB)')
                            ->disk('public')
                            ->directory('proposal/surat-undangan')
                            ->maxSize(5120)
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->downloadable()
                            ->openable()
                            ->previewable(false),

                        FileUpload::make('brosur')
                            ->label('Upload Brosur / Flyer')
                            ->helperText('Format PDF, JPG, atau PNG (Maks. 5MB)')
                            ->disk('public')
                            ->directory('proposal/brosur')
                            ->maxSize(5120)
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->downloadable()
                            ->openable(),

                    ])
                    ->columns(['default' => 1, 'sm' => 2])
                    ->columnSpanFull(),

                Section::make('BAB I — Pendahuluan')
                    ->icon('heroicon-o-book-open')
                    ->description('Latar belakang dan dasar pertimbangan kegiatan')
                    ->collapsible()
                    ->schema([

                        RichEditor::make('latar_belakang')
                            ->label('Latar Belakang')
                            ->placeholder('Uraikan latar belakang perlunya mengikuti/menyelenggarakan kegiatan ini...')
                            ->columnSpanFull(),

                        RichEditor::make('dasar_kegiatan')
                            ->label('Dasar Kegiatan')
                            ->placeholder('Sebutkan program kerja, anggaran madrasah, atau instruksi terkait...')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

                Section::make('BAB II — Tujuan Kegiatan')
                    ->icon('heroicon-o-flag')
                    ->description('Maksud dan sasaran yang hendak dicapai dari kegiatan')
                    ->collapsible()
                    ->schema([

                        RichEditor::make('tujuan')
                            ->label('Tujuan Kegiatan')
                            ->placeholder('Tuliskan tujuan umum dan khusus yang diharapkan...')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

                Section::make('BAB V — Penutup')
                    ->icon('heroicon-o-check-circle')
                    ->description('Harapan dan permohonan dukungan kepada pimpinan madrasah')
                    ->collapsible()
                    ->schema([

                        RichEditor::make('penutup')
                            ->label('Penutup')
                            ->placeholder('Tuliskan kesimpulan dan permohonan persetujuan proposal...')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}
