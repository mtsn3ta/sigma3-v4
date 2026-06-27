<?php

namespace App\Filament\Resources\LaporanBulanans\Pages;

use App\Filament\Resources\LaporanBulanans\LaporanBulananResource;
use App\Models\Ekstrakurikuler;
use App\Services\LaporanBulananGenerator;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ListLaporanBulanans extends ListRecords
{
    protected static string $resource = LaporanBulananResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('buatLaporanBulanan')

                ->label('Buat Laporan Bulanan')

                ->icon('heroicon-o-document-text')

                ->color('primary')

                ->modalHeading('Buat Laporan Bulanan')

                ->form([

                    Select::make('ekstrakurikuler_id')
                        ->label('Ekstrakurikuler')
                        ->options(function () {
                            /** @var User|null $user */
                            $user = Auth::user();

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
                        ->required(),

                    Select::make('bulan')
                        ->label('Bulan')
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
                        ->label('Tahun')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),

                ])

                ->action(function (array $data) {

                    $laporan = LaporanBulananGenerator::generate(

                        ekstrakurikulerId: (int) $data['ekstrakurikuler_id'],

                        bulan: (int) $data['bulan'],

                        tahun: (int) $data['tahun'],

                        createdById: Auth::id(),

                    );

                    return redirect(
                        LaporanBulananResource::getUrl(
                            'edit',
                            [
                                'record' => $laporan,
                            ]
                        )
                    );
                }),

        ];
    }
}
