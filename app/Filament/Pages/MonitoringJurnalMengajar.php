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
    protected string $view = 'filament.pages.monitoring-jurnal-mengajar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static ?string $navigationLabel = 'Monitoring Jurnal Mengajar';
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';
    protected static ?int $navigationSort = 3;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    DatePicker::make('tanggal_mulai')->label('Tanggal Mulai')->default(now('Asia/Jakarta')->toDateString())->required(),
                    DatePicker::make('tanggal_selesai')->label('Tanggal Selesai')->default(now('Asia/Jakarta')->toDateString())->required(),
                    Select::make('kelas')->label('Kelas')->options(KelasHelper::options())->required(),
                ])
                ->action(function (array $data) {
                    $url = route('print.monitoring-jurnal', [
                        'tanggal_mulai' => $data['tanggal_mulai'],
                        'tanggal_selesai' => $data['tanggal_selesai'],
                        'kelas' => $data['kelas'],
                    ]);
                    $this->redirect($url, navigate: false);
                }),
        ];
    }

    // === Mode Per Kelas (rentang tanggal) — TIDAK BERUBAH ===
    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;
    public ?int $kelasId = null;
    public array $timeline = [];
    public int $totalMengajar = 0;
    public int $totalIzin = 0;
    public int $totalKosong = 0;
    public int $totalAlpa = 0;
    public bool $sudahDicari = false;

    // === BARU: Mode Semua Kelas (1 hari) ===
    public string $modeTampilan = 'per_kelas'; // 'per_kelas' | 'semua_kelas'
    public ?string $tanggalSemuaKelas = null;
    public array $timelineSemuaKelas = [];
    public int $totalKelasSemuaKelas = 0;
    public int $totalMengajarSemuaKelas = 0;
    public int $totalIzinSemuaKelas = 0;
    public int $totalKosongSemuaKelas = 0;
    public int $totalAlpaSemuaKelas = 0;
    public bool $sudahDicariSemuaKelas = false;

    public function mount(): void
    {
        $this->tanggalMulai = now('Asia/Jakarta')->toDateString();
        $this->tanggalSelesai = now('Asia/Jakarta')->toDateString();
        $this->tanggalSemuaKelas = now('Asia/Jakarta')->toDateString();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->can('jurnal.view') ?? false;
    }

    public function getDaftarKelasProperty()
    {
        return Kelas::query()->orderBy('nama_kelas')->pluck('nama_kelas', 'id');
    }

    public function getKelasProperty(): ?Kelas
    {
        if (! $this->kelasId) return null;
        return Kelas::find($this->kelasId);
    }

    public function ubahMode(string $mode): void
    {
        $this->modeTampilan = $mode;
    }

    public function setRentang(string $jenis): void
    {
        $now = now('Asia/Jakarta');
        match ($jenis) {
            'hari_ini' => [
                $this->tanggalMulai = $now->toDateString(),
                $this->tanggalSelesai = $now->toDateString(),
            ],
            'minggu_ini' => [
                $this->tanggalMulai = $now->copy()->startOfWeek()->toDateString(),
                $this->tanggalSelesai = $now->copy()->endOfWeek()->toDateString(),
            ],
            'bulan_ini' => [
                $this->tanggalMulai = $now->copy()->startOfMonth()->toDateString(),
                $this->tanggalSelesai = $now->copy()->endOfMonth()->toDateString(),
            ],
            default => null,
        };

        if ($this->kelasId) {
            $this->tampilkan();
        }
    }

    // BARU: shortcut tanggal untuk mode semua kelas
    public function setTanggalSemuaKelas(string $jenis): void
    {
        $now = now('Asia/Jakarta');
        $this->tanggalSemuaKelas = match ($jenis) {
            'kemarin' => $now->copy()->subDay()->toDateString(),
            'hari_ini' => $now->toDateString(),
            default => $this->tanggalSemuaKelas,
        };

        $this->tampilkanSemuaKelas();
    }

    public function getHariBermasalahProperty(): array
    {
        return collect($this->timeline)
            ->filter(fn($h) => $h['totalAlpa'] > 0 || $h['totalKosong'] > 0)
            ->map(fn($h) => [
                'tanggal' => $h['tanggal'],
                'totalAlpa' => $h['totalAlpa'],
                'totalKosong' => $h['totalKosong'],
            ])
            ->values()
            ->toArray();
    }

    // BARU: daftar guru yang belum isi jurnal, lintas semua kelas, hari itu
    public function getGuruBelumIsiProperty(): array
    {
        $list = [];
        foreach ($this->timelineSemuaKelas as $item) {
            foreach ($item['timeline'] as $row) {
                if ($row['status'] === 'alpa') {
                    $list[] = [
                        'guru' => $row['guru'],
                        'kelas' => $item['kelas']->nama_kelas,
                        'jam' => $row['jam'],
                        'mapel' => $row['mapel'],
                    ];
                }
            }
        }
        return $list;
    }

    public function tampilkan(): void
    {
        $this->sudahDicari = true;

        if (! $this->tanggalMulai || ! $this->tanggalSelesai || ! $this->kelasId) {
            $this->timeline = [];
            $this->totalMengajar = 0;
            $this->totalIzin = 0;
            $this->totalKosong = 0;
            $this->totalAlpa = 0;
            return;
        }

        /** @var MonitoringJurnalService $service */
        $service = app(MonitoringJurnalService::class);

        $this->timeline = [];
        $this->totalMengajar = 0;
        $this->totalIzin = 0;
        $this->totalKosong = 0;
        $this->totalAlpa = 0;

        $tanggal = Carbon::parse($this->tanggalMulai)->locale('id');
        $tanggalSelesai = Carbon::parse($this->tanggalSelesai)->locale('id');

        while ($tanggal->lte($tanggalSelesai)) {
            $timelineHarian = $service->buildTimeline($tanggal, $this->kelasId);

            $hitungAlpa = collect($timelineHarian)->where('status', 'alpa')->count();
            $hitungKosong = collect($timelineHarian)->where('status', 'kosong')->count();
            $hitungMengajar = collect($timelineHarian)->where('status', 'mengajar')->count();
            $hitungIzin = collect($timelineHarian)->where('status', 'izin')->count();

            $this->timeline[] = [
                'tanggal' => $tanggal->copy(),
                'timeline' => $timelineHarian,
                'totalMengajar' => $hitungMengajar,
                'totalIzin' => $hitungIzin,
                'totalKosong' => $hitungKosong,
                'totalAlpa' => $hitungAlpa,
            ];

            $this->totalMengajar += $hitungMengajar;
            $this->totalIzin += $hitungIzin;
            $this->totalKosong += $hitungKosong;
            $this->totalAlpa += $hitungAlpa;

            $tanggal->addDay();
        }
    }

    // BARU: tampilkan rekap semua kelas untuk 1 tanggal
    public function tampilkanSemuaKelas(): void
    {
        $this->sudahDicariSemuaKelas = true;

        if (! $this->tanggalSemuaKelas) {
            $this->timelineSemuaKelas = [];
            return;
        }

        /** @var MonitoringJurnalService $service */
        $service = app(MonitoringJurnalService::class);

        $tanggal = Carbon::parse($this->tanggalSemuaKelas)->locale('id');

        $this->timelineSemuaKelas = $service->buildTimelineSemuaKelas($tanggal);

        $this->totalKelasSemuaKelas = count($this->timelineSemuaKelas);
        $this->totalMengajarSemuaKelas = collect($this->timelineSemuaKelas)->sum('totalMengajar');
        $this->totalIzinSemuaKelas = collect($this->timelineSemuaKelas)->sum('totalIzin');
        $this->totalKosongSemuaKelas = collect($this->timelineSemuaKelas)->sum('totalKosong');
        $this->totalAlpaSemuaKelas = collect($this->timelineSemuaKelas)->sum('totalAlpa');
    }
}
