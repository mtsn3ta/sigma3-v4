<?php

namespace App\Livewire;

use App\Models\Siswa;
use Filament\Actions\Action; // Gunakan Action dari Filament\Actions
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use App\Models\TahunAjaran;
use Livewire\Component;
use Filament\Forms\Components\Select;
use App\Models\Kelas;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use App\Exports\KehadiranSekolah\RekapSemesterExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapSemesterTable extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions; // Wajib ada sesuai contoh Anda
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

                Grid::make(3)
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
                            ->label('Kelas')
                            ->options(
                                Kelas::orderBy('tingkat')
                                    ->orderBy('nama_kelas')
                                    ->pluck('nama_kelas', 'id')
                            )
                            ->placeholder('Semua Kelas')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn() => $this->resetTable()),

                    ])

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

                            $query->where('kelas_id', $kelasId)

                        )

                    )

                    ->with(['kelasAktif.kelas'])

                    ->withCount([

                        'kehadiranSekolah as hadir_count' => fn(Builder $query) =>

                        $query
                            ->whereBetween(
                                'tanggal',
                                $this->getRentangSemester()
                            )
                            ->where('status', 'hadir'),

                        'kehadiranSekolah as terlambat_count' => fn(Builder $query) =>

                        $query
                            ->whereBetween(
                                'tanggal',
                                $this->getRentangSemester()
                            )
                            ->where('status', 'terlambat'),

                        'kehadiranSekolah as izin_count' => fn(Builder $query) =>

                        $query
                            ->whereBetween(
                                'tanggal',
                                $this->getRentangSemester()
                            )
                            ->where('status', 'izin'),

                        'kehadiranSekolah as sakit_count' => fn(Builder $query) =>

                        $query
                            ->whereBetween(
                                'tanggal',
                                $this->getRentangSemester()
                            )
                            ->where('status', 'sakit'),

                        'kehadiranSekolah as alfa_count' => fn(Builder $query) =>

                        $query
                            ->whereBetween(
                                'tanggal',
                                $this->getRentangSemester()
                            )
                            ->where('status', 'alfa'),

                    ])

            )
            ->columns([

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelasAktif.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge(),

                Tables\Columns\TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->badge()
                    ->color('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('terlambat_count')
                    ->label('Terlambat')
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('izin_count')
                    ->label('Izin')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('sakit_count')
                    ->label('Sakit')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('alfa_count')
                    ->label('Alfa')
                    ->badge()
                    ->color('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('persentase')
                    ->label('% Kehadiran')
                    ->badge()
                    ->alignCenter()
                    ->state(function (Siswa $record) {

                        // Terlambat tetap dihitung hadir
                        $totalHadir =
                            $record->hadir_count +
                            $record->terlambat_count;

                        $totalHari =
                            $record->hadir_count +
                            $record->terlambat_count +
                            $record->izin_count +
                            $record->sakit_count +
                            $record->alfa_count;

                        return $totalHari
                            ? round(($totalHadir / $totalHari) * 100) . '%'
                            : '0%';
                    })
                    ->color(function (string $state) {

                        $persentase = (int) str_replace('%', '', $state);

                        return match (true) {

                            $persentase >= 95 => 'success',

                            $persentase >= 85 => 'warning',

                            default => 'danger',
                        };
                    }),

            ])
            ->headerActions([
                // Gunakan Tables\Actions\Action untuk header actions agar sinkron
                Action::make('export_excel')

                    ->label('Export Excel')

                    ->icon('heroicon-o-document-arrow-down')

                    ->color('success')

                    ->action(function () {

                        return Excel::download(

                            new RekapSemesterExport(

                                $this->getTahun(),

                                $this->getSemester(),

                                $this->getKelasId(),

                            ),

                            'rekap-kehadiran-semester-'
                                . now()->format('YmdHis')
                                . '.xlsx'

                        );
                    }),
            ])
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.rekap-semester-table');
    }
}
