<?php

namespace App\Filament\Resources\Prestasis\Schemas;

use Filament\Schemas\Schema;
use App\Models\AnggotaEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Get;

class PrestasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Prestasi')

                    ->schema([

                        Select::make('ekstrakurikuler_id')

                            ->label('Ekstrakurikuler')

                            ->searchable()

                            ->preload()

                            ->native(false)

                            ->placeholder('Tidak terkait ekstrakurikuler')
                            ->live()
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
                            }),

                        TextInput::make('nama_prestasi')

                            ->label('Nama Prestasi')

                            ->placeholder('Contoh: Juara 1 Futsal Putra')

                            ->required()

                            ->maxLength(150),

                        TextInput::make('nama_lomba')

                            ->label('Nama Lomba')

                            ->placeholder('Contoh: PORSENI MTs Kabupaten Tulungagung')

                            ->required()

                            ->maxLength(150),

                        Select::make('jenis')

                            ->options([

                                'individu' => 'Individu',

                                'tim' => 'Tim',

                            ])

                            ->native(false)

                            ->required(),

                        Select::make('tingkat')

                            ->label('Tingkat')

                            ->options([

                                'kecamatan' => 'Kecamatan',

                                'kabupaten' => 'Kabupaten',

                                'karesidenan' => 'Karesidenan',

                                'provinsi' => 'Provinsi',

                                'nasional' => 'Nasional',

                                'internasional' => 'Internasional',

                            ])

                            ->native(false)

                            ->searchable()

                            ->required(),

                        DatePicker::make('tanggal')
                            ->required()
                            ->default(now()),

                    ])

                    ->columns(2),

                Section::make('Peserta')

                    ->schema([

                        MultiSelect::make('siswa_ids')

                            ->label('Siswa')
                            ->live()
                            ->relationship(
                                'siswa',
                                'nama'
                            )

                            ->searchable()

                            ->preload()

                            ->options(function (Get $get) {

                                $ekstrakurikulerId = $get('ekstrakurikuler_id');

                                /*
        |--------------------------------------------------------------------------
        | Prestasi Non Ekstrakurikuler
        |--------------------------------------------------------------------------
        */

                                if (! $ekstrakurikulerId) {

                                    return Siswa::query()

                                        ->orderBy('nama')

                                        ->pluck('nama', 'id');
                                }

                                /*
        |--------------------------------------------------------------------------
        | Prestasi Ekstrakurikuler
        |--------------------------------------------------------------------------
        */

                                return Siswa::query()

                                    ->whereIn(
                                        'id',
                                        AnggotaEkskul::query()

                                            ->where(
                                                'ekstrakurikuler_id',
                                                $ekstrakurikulerId
                                            )

                                            ->where(
                                                'status',
                                                'aktif'
                                            )

                                            ->pluck('siswa_id')
                                    )

                                    ->orderBy('nama')

                                    ->pluck('nama', 'id');
                            })

                            ->required()

                            ->columnSpanFull(),

                    ]),

                Section::make('Dokumentasi')

                    ->schema([

                        RichEditor::make('keterangan')

                            ->label('Keterangan')

                            ->columnSpanFull(),

                        FileUpload::make('foto')

                            ->label('Foto Prestasi')

                            ->disk('public')

                            ->directory('prestasi')

                            ->image()

                            ->imageEditor()

                            ->downloadable()

                            ->openable()

                            ->columnSpanFull(),

                    ])

                    ->columnSpanFull(),

            ]);
    }
}
