<?php

namespace App\Filament\Resources\SesiKegiatans\Schemas;

use App\Models\Ekstrakurikuler;
use App\Models\Pegawai;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Auth;

class SesiKegiatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Sesi Kegiatan')
                    ->description('Tentukan ekstrakurikuler, pelatih, dan detail waktu pelaksanaan.')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpanFull()
                    ->schema([

                        // Entitas Utama
                        Select::make('ekstrakurikuler_id')
                            ->label('Ekstrakurikuler')
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
                                    ->pluck('nama', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn(callable $set) => $set('pelatih_id', null))
                            ->required()
                            ->columnSpanFull(),

                        Select::make('pelatih_id')
                            ->label('Pelatih')
                            ->options(function (callable $get) {

                                $ekstrakurikulerId = $get('ekstrakurikuler_id');

                                if (blank($ekstrakurikulerId)) {
                                    return [];
                                }

                                return Pegawai::query()
                                    ->whereHas(
                                        'pengampus',
                                        fn($query) => $query->where(
                                            'ekstrakurikuler_id',
                                            $ekstrakurikulerId,
                                        ),
                                    )
                                    ->pluck('nama', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal')
                            ->label('Tanggal Kegiatan')
                            ->default(now())
                            ->native(false)
                            ->required(),

                        // Pengaturan Waktu
                        Grid::make(2)->schema([
                            TimePicker::make('waktu_mulai')
                                ->label('Waktu Mulai')
                                ->seconds(false)
                                ->required(fn(callable $get) => $get('status') !== 'draft'),

                            TimePicker::make('waktu_selesai')
                                ->label('Waktu Selesai')
                                ->seconds(false)
                                ->required(fn(callable $get) => $get('status') !== 'draft'),
                        ]),

                        Textarea::make('program_latihan')
                            ->label('Program Latihan')
                            ->placeholder('Tuliskan detail materi atau target latihan hari ini...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Radio::make('status')
                            ->label('Status Sesi')
                            ->options([
                                'draft' => 'Draft / Belum Lengkap',
                                'aktif' => 'Aktif / Berjalan',
                                'selesai' => 'Selesai',
                                'batal' => 'Dibatalkan',
                            ])
                            ->live()
                            ->inline()
                            ->default('aktif')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
