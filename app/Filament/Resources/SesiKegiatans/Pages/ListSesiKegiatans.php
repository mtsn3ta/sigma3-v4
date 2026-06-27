<?php

namespace App\Filament\Resources\SesiKegiatans\Pages;

use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use App\Models\Ekstrakurikuler;
use App\Services\SesiKegiatanGenerator;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ListSesiKegiatans extends ListRecords
{
    protected static string $resource = SesiKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('generate')

                ->label('Generate Sesi')

                ->icon('heroicon-o-calendar-days')

                ->color('success')

                ->form([

                    Select::make('ekstrakurikuler_id')

                        ->label('Ekstrakurikuler')

                        ->required()

                        ->searchable()

                        ->preload()

                        ->native(false)

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
                                return Ekstrakurikuler::pluck(
                                    'nama',
                                    'id'
                                );
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

                            return $user->pegawai?->pengampus()->value('ekstrakurikuler_id');
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

                            return $user->pegawai
                                ? $user->pegawai->pengampus()->count() === 1
                                : false;
                        })

                        ->dehydrated(),

                    Select::make('bulan')

                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ])

                        ->default(now()->month)

                        ->required(),

                    TextInput::make('tahun')

                        ->numeric()

                        ->default(now()->year)

                        ->required(),

                ])

                ->action(function (array $data) {

                    $jumlah = SesiKegiatanGenerator::generate(

                        ekstrakurikulerId: $data['ekstrakurikuler_id'],

                        bulan: (int) $data['bulan'],

                        tahun: (int) $data['tahun'],

                        pelatihId: Auth::user()->pegawai?->id,

                    );

                    Notification::make()

                        ->success()

                        ->title("{$jumlah} sesi berhasil dibuat.")

                        ->send();
                }),

            CreateAction::make()
                ->label('Tambah Sesi Baru'),

        ];
    }
}
