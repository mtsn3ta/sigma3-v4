<?php

namespace App\Livewire;

use App\Exports\KehadiranIbadah\RekapSemesterIbadahExport;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Schemas\Components\Section;

class RekapSemesterIbadahTable extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'tahun' => TahunAjaran::where('aktif', true)->value('tahun'),
            'semester' => 'ganjil',
            'kelas_id' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Membungkus filter dalam Section agar serasi dengan form lainnya
                Section::make()
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 3])
                            ->schema([
                                Select::make('tahun')
                                    ->label('Tahun Ajaran')
                                    ->options(
                                        TahunAjaran::orderByDesc('tahun')
                                            ->pluck('tahun', 'tahun')
                                    )
                                    ->default(
                                        TahunAjaran::where('aktif', true)
                                            ->value('tahun')
                                    )
                                    ->live()
                                    ->afterStateUpdated(fn() => $this->resetTable()),

                                Select::make('semester')
                                    ->label('Semester')
                                    ->options([
                                        'ganjil' => 'Ganjil',
                                        'genap'  => 'Genap',
                                    ])
                                    ->default('ganjil')
                                    ->live()
                                    ->afterStateUpdated(fn() => $this->resetTable()),

                                Select::make('kelas_id')
                                    ->label('Filter Kelas')
                                    ->options(
                                        Kelas::orderBy('tingkat')
                                            ->orderBy('nama_kelas')
                                            ->pluck('nama_kelas', 'id')
                                    )
                                    ->placeholder('Semua Kelas')
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn() => $this->resetTable()),
                            ]),
                    ])
                    ->compact(), // Padding minimal agar rapi
            ])
            ->statePath('data');
    }

    protected function getSemester(): string
    {
        return $this->data['semester'] ?? 'ganjil';
    }

    protected function getTahun(): string
    {
        return $this->data['tahun']
            ?? TahunAjaran::where('aktif', true)->value('tahun');
    }

    protected function getKelasId(): ?int
    {
        return $this->data['kelas_id'];
    }
    protected function getRentangSemester(): array
    {
        [$tahunAwal, $tahunAkhir] = explode('/', $this->getTahun());

        if ($this->getSemester() === 'ganjil') {

            return [

                "{$tahunAwal}-07-01",

                "{$tahunAwal}-12-31",

            ];
        }

        return [

            "{$tahunAkhir}-01-01",

            "{$tahunAkhir}-06-30",

        ];
    }
    protected function countIbadahSemester(
        string $jenis,
        string $status,
    ): \Closure {

        return function (Builder $query) use ($jenis, $status) {

            $query
                ->whereBetween(
                    'tanggal',
                    $this->getRentangSemester()
                )
                ->where('jenis', $jenis)
                ->where('status', $status);
        };
    }

    public function table(Table $table): Table
    {
        return $table

            ->query(

                Siswa::query()

                    ->when(

                        $this->getKelasId(),

                        fn(Builder $query, $kelasId) =>

                        $query->whereHas(

                            'kelasAktif',

                            fn(Builder $query) =>

                            $query->where(
                                'kelas_id',
                                $kelasId
                            )

                        )

                    )

                    ->with([
                        'kelasAktif.kelas',
                    ])

                    ->withCount([

                        'kehadiranIbadah as dhuha_hadir'
                        => $this->countIbadahSemester('dhuha', 'hadir'),

                        'kehadiranIbadah as dhuha_menstruasi'
                        => $this->countIbadahSemester('dhuha', 'menstruasi'),

                        'kehadiranIbadah as dhuha_tidak_hadir'
                        => $this->countIbadahSemester('dhuha', 'tidak_hadir'),

                        'kehadiranIbadah as dzuhur_hadir'
                        => $this->countIbadahSemester('dzuhur', 'hadir'),

                        'kehadiranIbadah as dzuhur_menstruasi'
                        => $this->countIbadahSemester('dzuhur', 'menstruasi'),

                        'kehadiranIbadah as dzuhur_tidak_hadir'
                        => $this->countIbadahSemester('dzuhur', 'tidak_hadir'),

                    ])

            )

            ->columns([

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelasAktif.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge(),

                Tables\Columns\TextColumn::make('dhuha')
                    ->label('DHUHA')
                    ->badge()
                    ->alignCenter()
                    ->state(function (Siswa $record) {

                        if ($record->dhuha_hadir > 0) {
                            return '✓';
                        }

                        if ($record->dhuha_menstruasi > 0) {
                            return 'M';
                        }

                        if ($record->dhuha_tidak_hadir > 0) {
                            return '✕';
                        }

                        return '-';
                    })
                    ->color(fn(string $state) => match ($state) {

                        '✓' => 'success',

                        'M' => 'warning',

                        '✕' => 'danger',

                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('dzuhur')
                    ->label('DZUHUR')
                    ->badge()
                    ->alignCenter()
                    ->state(function (Siswa $record) {

                        if ($record->dzuhur_hadir > 0) {
                            return '✓';
                        }

                        if ($record->dzuhur_menstruasi > 0) {
                            return 'M';
                        }

                        if ($record->dzuhur_tidak_hadir > 0) {
                            return '✕';
                        }

                        return '-';
                    })
                    ->color(fn(string $state) => match ($state) {

                        '✓' => 'success',

                        'M' => 'warning',

                        '✕' => 'danger',

                        default => 'gray',
                    }),

            ])

            ->headerActions([

                Action::make('export_excel')

                    ->label('Export Excel')

                    ->icon('heroicon-o-document-arrow-down')

                    ->color('success')

                    ->action(function () {

                        return Excel::download(

                            new RekapSemesterIbadahExport(

                                $this->getTahun(),

                                $this->getSemester(),

                                $this->getKelasId(),

                            ),

                            'rekap-kehadiran-ibadah-semester-'
                                . now()->format('YmdHis')
                                . '.xlsx'

                        );
                    }),

            ])

            ->striped();
    }

    public function render(): View
    {
        return view('livewire.rekap-semester-ibadah-table');
    }
}
