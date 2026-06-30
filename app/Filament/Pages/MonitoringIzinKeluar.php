<?php

namespace App\Filament\Pages;

use App\Models\IzinKeluar;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MonitoringIzinKeluar extends Page
{
    protected string $view =
    'filament.pages.monitoring-izin-keluar';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedArrowRightStartOnRectangle;

    protected static ?string $navigationLabel =
    'Monitoring Izin Keluar';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 5;

    public ?string $tanggalMulai = null;

    public ?string $tanggalSelesai = null;

    public Collection $data;

    public int $total = 0;

    public int $totalBelumKembali = 0;

    public int $totalSudahKembali = 0;

    public function mount(): void
    {
        $this->tanggalMulai = now()->toDateString();

        $this->tanggalSelesai = now()->toDateString();

        $this->data = collect();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can('izin_keluar.view') ?? false;
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
                        ->default(now())
                        ->required(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        ->default(now())
                        ->required(),

                ])

                ->action(function (array $data) {

                    $this->redirect(
                        route(
                            'print.monitoring-izin-keluar',
                            [

                                'tanggal_mulai' => $data['tanggal_mulai'],

                                'tanggal_selesai' => $data['tanggal_selesai'],

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
            ! $this->tanggalSelesai
        ) {

            $this->data = collect();

            $this->total = 0;

            $this->totalBelumKembali = 0;

            $this->totalSudahKembali = 0;

            return;
        }

        $this->data = IzinKeluar::query()

            ->with([
                'siswa.kelasAktif.kelas',
                'pegawai',
            ])

            ->whereBetween(
                'tanggal',
                [
                    Carbon::parse($this->tanggalMulai),
                    Carbon::parse($this->tanggalSelesai),
                ]
            )

            ->orderBy('tanggal')

            ->orderBy('jam_keluar')

            ->get();

        $this->total = $this->data->count();

        $this->totalBelumKembali = $this->data

            ->whereNull('jam_kembali')

            ->count();

        $this->totalSudahKembali = $this->data

            ->whereNotNull('jam_kembali')

            ->count();
    }
}
