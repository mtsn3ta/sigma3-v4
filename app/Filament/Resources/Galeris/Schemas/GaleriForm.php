<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Schemas\Schema;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Galeri')

                    ->schema([

                        Select::make('ekstrakurikuler_id')

                            ->label('Ekstrakurikuler')

                            ->searchable()

                            ->preload()

                            ->native(false)

                            ->placeholder('Galeri Sekolah')

                            ->options(function () {

                                /** @var User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return [];
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {

                                    return Ekstrakurikuler::query()

                                        ->orderBy('nama')

                                        ->pluck('nama', 'id');
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

                                    ->orderBy('nama')

                                    ->pluck('nama', 'id');
                            })

                            ->default(function () {

                                /** @var User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return null;
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {
                                    return null;
                                }

                                $ids = $user->pegawai
                                    ? $user->pegawai
                                    ->pengampus()
                                    ->pluck('ekstrakurikuler_id')
                                    : collect();

                                return $ids->count() === 1
                                    ? $ids->first()
                                    : null;
                            })

                            ->disabled(function () {

                                /** @var User|null $user */
                                $user = Auth::user();

                                if (! $user) {
                                    return false;
                                }

                                if (
                                    $user->hasRole('super_admin')
                                    || $user->hasRole('waka_kesiswaan')
                                    || $user->hasRole('kepala_madrasah')
                                ) {
                                    return false;
                                }

                                $ids = $user->pegawai
                                    ? $user->pegawai
                                    ->pengampus()
                                    ->pluck('ekstrakurikuler_id')
                                    : collect();

                                return $ids->count() === 1;
                            })

                            ->live(),

                        TextInput::make('judul')

                            ->label('Judul')

                            ->placeholder('Contoh: Latihan Rutin Minggu Ke-2')

                            ->required()

                            ->maxLength(150),

                        Select::make('kategori')

                            ->label('Kategori')

                            ->options([

                                'sekolah' => 'Sekolah',

                                'ekstrakurikuler' => 'Ekstrakurikuler',

                                'prestasi' => 'Prestasi',

                                'kegiatan' => 'Kegiatan',

                            ])

                            ->native(false)

                            ->required(),

                        DatePicker::make('tanggal')

                            ->label('Tanggal')

                            ->default(now())

                            ->required(),

                    ])

                    ->columns(2),

                Section::make('Dokumentasi')

                    ->schema([

                        RichEditor::make('deskripsi')

                            ->label('Deskripsi')

                            ->columnSpanFull(),

                        FileUpload::make('foto_baru')

                            ->label('Foto Album')

                            ->multiple()

                            ->reorderable()

                            ->image()

                            ->imageEditor()

                            ->disk('public')

                            ->directory('galeri')

                            ->required(fn(string $operation) => $operation === 'create')

                            ->visible(fn(string $operation) => $operation === 'create')

                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull(),

            ]);
    }
}
