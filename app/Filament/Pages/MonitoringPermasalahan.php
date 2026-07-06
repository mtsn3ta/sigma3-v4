<?php

namespace App\Filament\Pages;

use App\Models\Kelas;
use App\Models\PermasalahanSiswa;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MonitoringPermasalahan extends Page
{
    protected string $view =
    'filament.pages.monitoring-permasalahan';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel =
    'Monitoring Permasalahan BK';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 6;

    public ?string $tanggalMulai = null;

    public ?string $tanggalSelesai = null;

    public ?int $kelasId = null;

    public Collection $data;

    public int $totalPermasalahan = 0;

    public int $totalSiswa = 0;

    public int $totalPoin = 0;

    public function mount(): void
    {
        $this->tanggalMulai = now()->startOfMonth()->toDateString();

        $this->tanggalSelesai = now()->endOfMonth()->toDateString();

        $this->data = collect();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can(
            'masalah.view'
        ) ?? false;
    }

    public function getKelasProperty(): ?Kelas
    {
        return $this->kelasId
            ? Kelas::find($this->kelasId)
            : null;
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('exportPdf')

                ->label('Export PDF')

                ->icon('heroicon-o-document-arrow-down')

                ->color('danger')

                ->form([

                    DatePicker::make('tanggal_mulai')
                        ->label('Tanggal Mulai')
                        ->default(now()->startOfMonth())
                        ->required(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->default(now()->endOfMonth())
                        ->required(),

                    Select::make('kelas')
                        ->label('Kelas')
                        ->options(\App\Support\KelasHelper::options())
                        ->required(),

                ])

                ->action(function (array $data) {

                    $this->redirect(
                        route(
                            'print.monitoring-permasalahan',
                            [

                                'tanggal_mulai' => $data['tanggal_mulai'],

                                'tanggal_selesai' => $data['tanggal_selesai'],

                                'kelas' => $data['kelas'],

                            ]
                        ),
                        navigate: false
                    );
                }),

        ];
    }

    public function tampilkan(): void
    {
        if (
            ! $this->tanggalMulai ||
            ! $this->tanggalSelesai ||
            ! $this->kelasId
        ) {

            $this->data = collect();

            $this->totalPermasalahan = 0;

            $this->totalSiswa = 0;

            $this->totalPoin = 0;

            return;
        }

        $this->data = PermasalahanSiswa::query()

            ->with([
                'pegawai',
                'siswa.kelasAktif.kelas',
            ])

            ->whereBetween(
                'tanggal',
                [
                    Carbon::parse($this->tanggalMulai),
                    Carbon::parse($this->tanggalSelesai),
                ]
            )

            ->whereHas(
                'siswa.kelasAktif',
                fn($query) =>

                $query->where(
                    'kelas_id',
                    $this->kelasId
                )
            )

            ->orderBy('tanggal')

            ->orderByDesc('poin')

            ->get();

        $this->totalPermasalahan =

            $this->data->count();

        $this->totalSiswa =

            $this->data

            ->pluck('siswa_id')

            ->unique()

            ->count();

        $this->totalPoin =

            $this->data

            ->sum('poin');
    }
}
