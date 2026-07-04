<?php

namespace App\Filament\Resources\KelasSiswas\Schemas;

use App\Models\TahunAjaran;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KelasSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Penempatan Siswa')
                    ->description('Tentukan siswa, kelas tujuan, dan tahun ajaran aktif.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        // Siswa diletakkan di baris sendiri karena ini entitas utama
                        Select::make('siswa_id')
                            ->label('Pilih Siswa')
                            ->relationship('siswa', 'nama')
                            ->searchable()
                            ->preload()
                            ->validationMessages([
                                'unique' => 'Siswa sudah terdaftar pada tahun ajaran tersebut.',
                            ])
                            ->required()
                            ->columnSpanFull(),

                        // Kelas dan Tahun Ajaran disandingkan
                        Grid::make(2)->schema([
                            Select::make('kelas_id')
                                ->label('Kelas Tujuan')
                                ->relationship('kelas', 'nama_kelas')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->relationship('tahunAjaran', 'tahun')
                                ->searchable()
                                ->preload()
                                ->default(TahunAjaran::where('aktif', true)->value('id'))
                                ->required(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
