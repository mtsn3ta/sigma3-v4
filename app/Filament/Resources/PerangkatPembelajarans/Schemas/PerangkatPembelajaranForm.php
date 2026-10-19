<?php

namespace App\Filament\Resources\PerangkatPembelajarans\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerangkatPembelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pegawai_id')
                    ->relationship('pegawai', 'nama')
                    ->hiddenOn('create')
                    ->required(),
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'tahun'),
                Select::make('kelas_id')
                    ->label('Kelas (opsional)')
                    ->relationship('kelas', 'nama_kelas')
                    ->helperText('Kosongkan jika perangkat ini berlaku untuk semua kelas yang Anda ajar.'),
                TextInput::make('mapel')
                    ->label('Mata Pelajaran'),
                TextInput::make('judul')
                    ->required(),
                Select::make('jenis')
                    ->options([
                        'RPP' => 'RPP',
                        'Modul Ajar' => 'Modul Ajar',
                        'Silabus' => 'Silabus',
                        'Prota' => 'Prota',
                        'Prosem' => 'Prosem',
                        'Bahan Ajar' => 'Bahan Ajar',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->default('Modul Ajar')
                    ->required(),
                FileUpload::make('file_path')
                    ->disk('public')
                    ->directory('perangkat-pembelajaran')
                    ->required(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ]);
    }
}
