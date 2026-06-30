<?php

namespace App\Filament\Pages;

use App\Models\Kelas;
use App\Models\User;
use App\Services\Monitoring\MonitoringJurnalService;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Support\KelasHelper;

class MonitoringJurnalMengajar extends Page
{
    protected string $view =
    'filament.pages.monitoring-jurnal-mengajar';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel =
    'Monitoring Jurnal Mengajar';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 3;

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

                    Select::make('kelas')
                        ->label('Kelas')
                        ->options(KelasHelper::options())
                        ->required(),

                ])

                ->action(function (array $data) {

                    $url = route(
                        'print.monitoring-jurnal',
                        [

                            'tanggal_mulai' => $data['tanggal_mulai'],

                            'tanggal_selesai' => $data['tanggal_selesai'],

                            'kelas' => $data['kelas'],

                        ]
                    );

                    $this->redirect(
                        $url,
                        navigate: false
                    );
                }),

        ];
    }

    public ?string $tanggalMulai = null;

    public ?string $tanggalSelesai = null;

    public ?int $kelasId = null;

    public array $timeline = [];

    public int $totalMengajar = 0;

    public int $totalIzin = 0;

    public int $totalKosong = 0;

    public function mount(): void
    {
        $this->tanggalMulai = now()->toDateString();

        $this->tanggalSelesai = now()->toDateString();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can('jurnal.view') ?? false;
    }

    public function getDaftarKelasProperty()
    {
        return Kelas::query()

            ->orderBy('nama_kelas')

            ->pluck(
                'nama_kelas',
                'id'
            );
    }

    public function getKelasProperty(): ?Kelas
    {
        if (! $this->kelasId) {
            return null;
        }

        return Kelas::find(
            $this->kelasId
        );
    }

    public function getHariProperty(): string
    {
        if (! $this->tanggal) {
            return '-';
        }

        return Carbon::parse(
            $this->tanggal
        )->translatedFormat('l');
    }

    public function tampilkan(): void
    {
        if (
            ! $this->tanggalMulai ||
            ! $this->tanggalSelesai ||
            ! $this->kelasId
        ) {

            $this->timeline = [];

            $this->totalMengajar = 0;

            $this->totalIzin = 0;

            $this->totalKosong = 0;

            return;
        }

        /** @var MonitoringJurnalService $service */
        $service = app(
            MonitoringJurnalService::class
        );

        $this->timeline = [];

        $this->totalMengajar = 0;

        $this->totalIzin = 0;

        $this->totalKosong = 0;

        $tanggal = Carbon::parse(
            $this->tanggalMulai
        );

        $tanggalSelesai = Carbon::parse(
            $this->tanggalSelesai
        );

        while ($tanggal->lte($tanggalSelesai)) {

            $timelineHarian = $service->buildTimeline(

                $tanggal,

                $this->kelasId,

            );

            $this->timeline[] = [

                'tanggal' => $tanggal->copy(),

                'timeline' => $timelineHarian,

                'totalMengajar' => collect($timelineHarian)

                    ->where('status', 'mengajar')

                    ->count(),

                'totalIzin' => collect($timelineHarian)

                    ->where('status', 'izin')

                    ->count(),

                'totalKosong' => collect($timelineHarian)

                    ->where('status', 'kosong')

                    ->count(),

            ];

            $this->totalMengajar += collect($timelineHarian)
                ->where('status', 'mengajar')
                ->count();

            $this->totalIzin += collect($timelineHarian)
                ->where('status', 'izin')
                ->count();

            $this->totalKosong += collect($timelineHarian)
                ->where('status', 'kosong')
                ->count();

            $tanggal->addDay();
        }
    }
}
