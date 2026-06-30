<?php

namespace App\Filament\Pages;

use App\Models\AnggotaEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerPengampu;
use App\Models\TahunAjaran;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MonitoringEkstrakurikuler extends Page
{
    protected string $view =
    'filament.pages.monitoring-ekstrakurikuler';

    protected static string|BackedEnum|null $navigationIcon =
    Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel =
    'Monitoring Ekstrakurikuler';

    protected static string|\UnitEnum|null $navigationGroup =
    'Monitoring';

    protected static ?int $navigationSort = 7;

    public ?int $tahunAjaranId = null;

    public string $semester = 'ganjil';

    public Collection $data;

    public int $totalEkskul = 0;

    public int $totalPembina = 0;

    public int $totalPelatih = 0;

    public int $totalAnggota = 0;

    public function mount(): void
    {
        $this->tahunAjaranId = TahunAjaran::query()

            ->where('aktif', true)

            ->value('id');

        $this->semester =

            now()->month <= 6

            ? 'genap'

            : 'ganjil';

        $this->data = collect();
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user?->can(
            'ekstrakurikuler.view'
        ) ?? false;
    }

    public function getDaftarTahunAjaranProperty()
    {
        return TahunAjaran::query()

            ->orderByDesc('tahun')

            ->pluck(
                'tahun',
                'id'
            );
    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('exportPdf')

                ->label('Export PDF')

                ->icon('heroicon-o-document-arrow-down')

                ->color('danger')

                ->form([

                    Select::make('tahun_ajaran')

                        ->label('Tahun Ajaran')

                        ->options(

                            TahunAjaran::query()

                                ->orderByDesc('tahun')

                                ->pluck(
                                    'tahun',
                                    'id'
                                )

                        )

                        ->default(
                            $this->tahunAjaranId
                        )

                        ->required(),

                    Select::make('semester')

                        ->label('Semester')

                        ->options([

                            'ganjil' => 'Ganjil',

                            'genap' => 'Genap',

                        ])

                        ->default(
                            $this->semester
                        )

                        ->required(),

                ])

                ->action(function (array $data) {

                    $this->redirect(

                        route(
                            'print.monitoring-ekstrakurikuler',
                            [

                                'tahun_ajaran' => $data['tahun_ajaran'],

                                'semester' => $data['semester'],

                            ]
                        ),

                        navigate: false

                    );
                }),

        ];
    }

    public function tampilkan(): void
    {
        if (! $this->tahunAjaranId) {

            $this->data = collect();

            $this->totalEkskul = 0;

            $this->totalPembina = 0;

            $this->totalPelatih = 0;

            $this->totalAnggota = 0;

            return;
        }

        $this->data = Ekstrakurikuler::query()

            ->with([

                'pengampu.pegawai',

                'anggotaEkskul' => fn($query)

                => $query->where(
                    'tahun_ajaran_id',
                    $this->tahunAjaranId
                ),

            ])

            ->orderBy('nama')

            ->get();

        $this->totalEkskul =

            $this->data->count();

        $this->totalPembina =

            EkstrakurikulerPengampu::query()

            ->where(
                'role',
                'pembina'
            )

            ->distinct('pegawai_id')

            ->count('pegawai_id');

        $this->totalPelatih =

            EkstrakurikulerPengampu::query()

            ->where(
                'role',
                'pelatih'
            )

            ->distinct('pegawai_id')

            ->count('pegawai_id');

        $this->totalAnggota =

            AnggotaEkskul::query()

            ->where(
                'tahun_ajaran_id',
                $this->tahunAjaranId
            )

            ->count();
    }
}
