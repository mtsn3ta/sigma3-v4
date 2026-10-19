<?php

namespace App\Filament\Resources\Prestasis\Schemas;

use Filament\Schemas\Schema;
use App\Models\AnggotaEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class PrestasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Prestasi')
                    ->description('Catat detail prestasi siswa, mulai dari nama lomba hingga tingkat pencapaian.')
                    ->icon('heroicon-o-trophy')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('ekstrakurikuler_id')
                            ->label('Ekstrakurikuler')
                            ->placeholder('Tidak terkait ekstrakurikuler')
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(Set $set) => $set('siswa', []))
                            ->options(fn() => self::getEkskulOptions())
                            ->default(fn() => self::getDefaultEkskul())
                            ->disabled(fn() => self::shouldDisableEkskul()),

                        TextInput::make('nama_prestasi')
                            ->label('Nama Prestasi')
                            ->placeholder('Contoh: Juara 1 Futsal Putra')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('nama_lomba')
                            ->label('Nama Lomba / Event')
                            ->placeholder('Contoh: PORSENI MTs Kabupaten')
                            ->required()
                            ->maxLength(150),

                        Select::make('tingkat')
                            ->label('Tingkat Kejuaraan')
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

                        Select::make('jenis')
                            ->label('Jenis Prestasi')
                            ->options(['individu' => 'Individu', 'tim' => 'Tim'])
                            ->native(false)
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Lomba')
                            ->default(now())
                            ->required(),
                    ]),

                Section::make('Peserta Prestasi')
                    ->description('Pilih siswa yang memperoleh prestasi.')
                    ->icon('heroicon-o-users')
                    ->columnSpanFull()
                    ->schema([

                        Select::make('siswa') // Gunakan nama relasi yang ada di Model
                            ->multiple()
                            ->relationship(
                                name: 'siswa',
                                titleAttribute: 'nama',
                                // Pindahkan logika filter ke dalam modifyQueryUsing
                                modifyQueryUsing: function (Builder $query, Get $get) {
                                    $query->where('siswa.status', 'aktif');

                                    $ekstrakurikulerId = $get('ekstrakurikuler_id');

                                    if (!$ekstrakurikulerId) {
                                        return $query;
                                    }

                                    return $query->whereIn(
                                        'siswa.id', // <--- UBAH BAGIAN INI (tambahkan siswa.)
                                        AnggotaEkskul::query()
                                            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
                                            ->where('status', 'aktif')
                                            ->select('siswa_id')
                                    );
                                }
                            )
                            ->label('Siswa Berprestasi')
                            ->live()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                    ]),

                Section::make('Dokumentasi')
                    ->icon('heroicon-o-camera')
                    ->columnSpanFull()
                    ->schema([
                        RichEditor::make('keterangan')
                            ->label('Catatan Tambahan')
                            ->columnSpanFull(),

                        FileUpload::make('foto')
                            ->label('Bukti Foto Prestasi')
                            ->disk('public')
                            ->directory('prestasi')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
    // Helper methods untuk menjaga controller tetap bersih
    private static function getEkskulOptions(): array
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (! $user) return [];

        // Jika admin/waka/kepala, tampilkan semua
        if ($user->hasRole(['super_admin', 'waka_kesiswaan', 'kepala_madrasah'])) {
            return Ekstrakurikuler::query()->orderBy('nama')->pluck('nama', 'id')->toArray();
        }

        // Jika pelatih/pembina, hanya tampilkan yang diampu
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
            ->pluck('nama', 'id')
            ->toArray();
    }

    private static function getDefaultEkskul(): ?int
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->hasRole(['super_admin', 'waka_kesiswaan', 'kepala_madrasah'])) {
            return null;
        }

        $ids = $user->pegawai?->pengampus()->pluck('ekstrakurikuler_id') ?? collect();
        return $ids->count() === 1 ? $ids->first() : null;
    }

    private static function shouldDisableEkskul(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user || $user->hasRole(['super_admin', 'waka_kesiswaan', 'kepala_madrasah'])) {
            return false;
        }

        $ids = $user->pegawai?->pengampus()->pluck('ekstrakurikuler_id') ?? collect();
        return $ids->count() === 1;
    }
}
