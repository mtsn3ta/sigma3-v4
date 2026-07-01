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
                    ->description('Informasi utama album dokumentasi kegiatan.')
                    ->icon('heroicon-o-photo')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('ekstrakurikuler_id')
                            ->label('Ekstrakurikuler')
                            ->placeholder('Galeri Sekolah')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->options(fn() => self::getEkskulOptions())
                            ->default(fn() => self::getDefaultEkskul())
                            ->disabled(fn() => self::shouldDisableEkskul()),

                        TextInput::make('judul')
                            ->label('Judul Galeri')
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
                    ]),

                Section::make('Dokumentasi')
                    ->description('Unggah foto-foto yang akan dimasukkan ke dalam album.')
                    ->icon('heroicon-o-camera')
                    ->columnSpanFull()
                    ->schema([
                        RichEditor::make('deskripsi')
                            ->label('Deskripsi'),

                        FileUpload::make('foto_baru')
                            ->label('Foto Dokumentasi')
                            ->multiple()
                            ->reorderable()
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('galeri')
                            ->downloadable()
                            ->openable()
                            ->required(fn($operation) => $operation === 'create')
                            ->visible(fn($operation) => $operation === 'create'),
                    ]),
            ]);
    }

    // Helper methods (gunakan logika yang sama dengan PrestasiForm sebelumnya)
    private static function getEkskulOptions(): array
    {
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

                ->pluck('nama', 'id')
                ->toArray();
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
    }
    private static function getDefaultEkskul(): ?int
    {
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
    }
    private static function shouldDisableEkskul(): bool
    {
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
    }
}
