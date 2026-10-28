<?php

namespace App\Filament\Resources\AgendaMadrasahs\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

class AgendaMadrasahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // === SECTION 1: Jenis & Nama Agenda ===
                Section::make('Jenis Agenda')
                    ->description('Tentukan jenis agenda terlebih dahulu, karena field lain menyesuaikan pilihan ini.')
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Select::make('tipe_agenda')
                            ->label('Tipe Agenda')
                            ->options([
                                'libur_nasional'   => '🔴 Libur Nasional (KBM Libur)',
                                'kegiatan_bersama' => '🔵 Kegiatan Madrasah (Semua Kelas)',
                                'kondisi_kelas'    => '🟡 Kondisi Khusus (Hanya Kelas Tertentu)',
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->columnSpanFull(),

                        TextInput::make('nama_agenda')
                            ->label('Nama Agenda / Acara')
                            ->placeholder('Contoh: Libur Semester, Pondok Ramadhan')
                            ->prefixIcon('heroicon-o-pencil-square')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Select::make('kelas_id')
                            ->label('Berlaku Untuk Kelas')
                            ->relationship('kelas', 'nama_kelas')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->prefixIcon('heroicon-o-user-group')
                            ->helperText('Wajib diisi karena tipe agenda ini spesifik untuk satu kelas.')
                            ->visible(fn(Get $get) => $get('tipe_agenda') === 'kondisi_kelas')
                            ->required(fn(Get $get) => $get('tipe_agenda') === 'kondisi_kelas')
                            ->columnSpanFull(),
                    ]),

                // === SECTION 2: Periode Tanggal ===
                Section::make('Periode Agenda')
                    ->description('Tentukan tanggal mulai dan selesai kegiatan.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('tanggal_mulai')
                                    ->label('Tanggal Mulai')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d F Y')
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->live(),

                                DatePicker::make('tanggal_selesai')
                                    ->label('Sampai Tanggal (Opsional)')
                                    ->native(false)
                                    ->displayFormat('d F Y')
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->afterOrEqual('tanggal_mulai')
                                    ->helperText('Biarkan kosong jika acara hanya berlangsung 1 hari.'),
                            ]),
                    ]),

                // === SECTION 3: Detail Tambahan ===
                Section::make('Detail Tambahan')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->placeholder('Catatan opsional terkait agenda ini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
