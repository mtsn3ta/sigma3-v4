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
            ->components([

                Section::make('Informasi Kegiatan')
                    ->schema([

                        Select::make('ekstrakurikuler_id')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->label('Ekstrakurikuler')
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
                            ->required(),

                        TextInput::make('nama_kegiatan')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->required()
                            ->maxLength(150),

                        Select::make('jenis_kegiatan')
                            ->label('Jenis Kegiatan')
                            ->options([
                                'lomba' => 'Lomba',
                                'festival' => 'Festival',
                                'kemah' => 'Kemah',
                                'pelatihan' => 'Pelatihan',
                                'seminar' => 'Seminar',
                                'workshop' => 'Workshop',
                                'studi_banding' => 'Studi Banding',
                                'kunjungan' => 'Kunjungan',
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
                            ->required()
                            ->maxLength(150),

                        Select::make('tingkat_kegiatan')
                            ->live()
                            ->afterStateUpdated(
                                fn(Get $get, Set $set) => ProposalTemplate::fill($get, $set)
                            )
                            ->label('Tingkat Kegiatan')
                            ->options([
                                'kecamatan' => 'Kecamatan',
                                'kabupaten' => 'Kabupaten',
                                'karesidenan' => 'Karesidenan',
                                'provinsi' => 'Provinsi',
                                'nasional' => 'Nasional',
                                'internasional' => 'Internasional',
                            ])
                            ->required(),

                        TextInput::make('tempat')
                            ->required()
                            ->maxLength(150),

                        DatePicker::make('tanggal_mulai')
                            ->native(false)
                            ->required(),

                        DatePicker::make('tanggal_selesai')
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Dokumen Pendukung')
                    ->schema([

                        TextInput::make('nomor_surat_undangan')
                            ->label('Nomor Surat Undangan'),

                        DatePicker::make('tanggal_surat_undangan')
                            ->label('Tanggal Surat')
                            ->native(false),

                        FileUpload::make('surat_undangan')
                            ->label('Upload Surat Undangan')
                            ->directory('proposal/surat-undangan')
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
                            ->directory('proposal/brosur')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                            ])
                            ->downloadable()
                            ->openable(),

                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('BAB I — Pendahuluan')
                    ->description('Latar belakang dan dasar kegiatan')
                    ->schema([

                        RichEditor::make('latar_belakang')
                            ->label('Latar Belakang')
                            ->columnSpanFull(),

                        RichEditor::make('dasar_kegiatan')
                            ->label('Dasar Kegiatan')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

                Section::make('BAB II — Tujuan')
                    ->schema([

                        RichEditor::make('tujuan')
                            ->label('Tujuan Kegiatan')
                            ->columnSpanFull(),

                    ])
                    ->columnSpanFull(),

                Section::make('BAB V — Penutup')
                    ->schema([

                        RichEditor::make('penutup')
                            ->label('Penutup')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

            ]);
    }
}
