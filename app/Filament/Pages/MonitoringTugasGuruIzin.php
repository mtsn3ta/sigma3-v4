<?php

namespace App\Filament\Pages;

use App\Models\TugasGuruIzin;
use App\Models\User;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Filament\Forms\Components\DatePicker;

class MonitoringTugasGuruIzin extends Page
{
    protected string $view = 'filament.pages.monitoring-tugas-guru-izin';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Monitoring Tugas Guru Izin';

    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 4;

    public ?string $tanggalMulai = null;

    public ?string $tanggalSelesai = null;

    public Collection $data;

    public int $totalBaru = 0;

    public int $totalDisampaikan = 0;

    public int $totalSelesai = 0;

    public function mount(): void
    {
        // PERBAIKAN 1: Kunci tanggal awal muat halaman monitoring ke Asia/Jakarta (WIB) agar akurat tanggal hari ini
        $this->tanggalMulai = now('Asia/Jakarta')->toDateString();

        $this->tanggalSelesai = now('Asia/Jakarta')->toDateString();

        $this->data = collect();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can('tugas_izin.view') ?? false;
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
                        // PERBAIKAN 2: Kunci default tanggal awal ekspor PDF ke Asia/Jakarta (WIB)
                        ->default(now('Asia/Jakarta')->toDateString())
                        ->required(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tanggal Selesai')
                        // PERBAIKAN 3: Kunci default tanggal akhir ekspor PDF ke Asia/Jakarta (WIB)
                        ->default(now('Asia/Jakarta')->toDateString())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route(
                        'print.monitoring-tugas-guru-izin',
                        [
                            'tanggal_mulai' => $data['tanggal_mulai'],
                            'tanggal_selesai' => $data['tanggal_selesai'],
                        ]
                    );

                    $this->redirect(
                        $url,
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
            $this->totalBaru = 0;
            $this->totalDisampaikan = 0;
            $this->totalSelesai = 0;
            return;
        }

        $this->data = TugasGuruIzin::query()
            ->with([
                'guru',
                'petugas',
                'kelas',
            ])
            ->whereBetween(
                'tanggal',
                [
                    // PERBAIKAN 4: Pastikan range pencarian aman dengan start/end of day berbasis timezone Asia/Jakarta
                    Carbon::parse($this->tanggalMulai, 'Asia/Jakarta')->startOfDay(),
                    Carbon::parse($this->tanggalSelesai, 'Asia/Jakarta')->endOfDay(),
                ]
            )
            ->orderBy('tanggal')
            ->orderBy('kelas_id')
            ->orderBy('jam_ke')
            ->get();

        $collection = $this->data->flatten();

        $this->totalBaru = $collection->where('status', 'baru')->count();

        $this->totalDisampaikan = $collection->where('status', 'disampaikan')->count();

        $this->totalSelesai = $collection->where('status', 'selesai')->count();
    }
}
