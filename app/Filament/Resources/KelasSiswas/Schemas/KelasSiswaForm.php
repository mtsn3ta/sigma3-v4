<?php

namespace App\Filament\Resources\KelasSiswas\Schemas;

use App\Models\TahunAjaran;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class KelasSiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->searchable()
                    ->preload()
                    ->validationMessages([
                        'unique' => 'Siswa sudah terdaftar pada tahun ajaran tersebut.',
                    ])
                    ->required(),

                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'tahun')
                    ->searchable()
                    ->preload()
                    ->default(
                        TahunAjaran::where('aktif', true)
                            ->value('id')
                    )
                    ->required(),

                Radio::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'naik' => 'Naik',
                        'lulus' => 'Lulus',
                    ])
                    ->default('aktif')
                    ->required(),

            ]);
    }
}
