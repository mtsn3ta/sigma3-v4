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
    protected string $view = 'filament.pages.monitoring-permasalahan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Monitoring Permasalahan BK';

    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

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
        // PERBAIKAN 1: Kunci penentuan awal dan akhir bulan ke zona waktu Asia/Jakarta (WIB)
        $this->tanggalMulai = now('Asia/Jakarta')->startOfMonth()->toDateString();

        $this->tanggalSelesai = now('Asia/Jakarta')->endOfMonth()->toDateString();

        $this->data = collect();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can('masalah.view') ?? false;
    }

    public function getKelasProperty(): ?Kelas
    {
        return $this->kelasId ? Kelas::find($this->kelasId) : null;
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
                        // PERBAIKAN 2: Kunci default tanggal ekspor PDF ke Asia/Jakarta (WIB)
                        ->default(now('Asia/Jakarta')->startOfMonth())
                        ->required(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        // PERBAIKAN 3: Kunci default tanggal ekspor PDF ke Asia/Jakarta (WIB)
                        ->default(now('Asia/Jakarta')->endOfMonth())
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
            // PERBAIKAN 4: Amankan relasi prapemuatan kelas menggunakan kelasSiswa agar Kelas 7 terbaca sempurna
            ->with([
                'pegawai',
                'siswa.kelasSiswa.kelas',
            ])
            ->whereBetween(
                'tanggal',
                [
                    Carbon::parse($this->tanggalMulai, 'Asia/Jakarta')->startOfDay(),
                    Carbon::parse($this->tanggalSelesai, 'Asia/Jakarta')->endOfDay(),
                ]
            )
            // PERBAIKAN 5: Saring data kelas langsung membidik tabel perantara kelasSiswa utama agar siswa Kelas 7 tidak hilang
            ->whereHas(
                'siswa.kelasSiswa',
                fn($query) => $query->where('kelas_id', $this->kelasId)
            )
            ->orderBy('tanggal')
            ->orderByDesc('poin')
            ->get();

        $this->totalPermasalahan = $this->data->count();

        $this->totalSiswa = $this->data
            ->pluck('siswa_id')
            ->unique()
            ->count();

        $this->totalPoin = $this->data->sum('poin');
    }
}
