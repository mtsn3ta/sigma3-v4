<?php

namespace App\Livewire;

use App\Exports\KehadiranIbadah\RekapBulananIbadahExport;
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
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Schemas\Components\Section;

class RekapBulananIbadahTable extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'bulan' => now()->month,
            'tahun' => now()->year,
            'kelas_id' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Membungkus filter dalam Section agar menjadi kartu yang rapi
                Section::make()
                    ->schema([
                        Grid::make(['default' => 1, 'md' => 3])
                            ->schema([
                                Select::make('bulan')
                                    ->label('Pilih Bulan')
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
                                    ->live()
                                    ->afterStateUpdated(fn() => $this->resetTable()),

                                Select::make('tahun')
                                    ->label('Pilih Tahun')
                                    ->options([
                                        now()->year - 1 => now()->year - 1,
                                        now()->year => now()->year,
                                        now()->year + 1 => now()->year + 1,
                                    ])
                                    ->default(now()->year)
                                    ->live()
                                    ->afterStateUpdated(fn() => $this->resetTable()),

                                Select::make('kelas_id')
                                    ->label('Filter Kelas')
                                    ->options(
                                        Kelas::orderBy('tingkat')
                                            ->orderBy('nama_kelas')
                                            ->pluck('nama_kelas', 'id')
                                    )
                                    ->searchable()
                                    ->placeholder('Semua Kelas')
                                    ->live()
                                    ->afterStateUpdated(fn() => $this->resetTable()),
                            ]),
                    ])
                    ->compact(), // Padding minimal agar rapi sebagai filter
            ])
            ->statePath('data');
    }

    protected function getBulan(): int
    {
        return $this->data['bulan'] ?? now()->month;
    }

    protected function getTahun(): int
    {
        return $this->data['tahun'] ?? now()->year;
    }

    protected function getKelasId(): ?int
    {
        return $this->data['kelas_id'] ?? null;
    }

    protected function countIbadah(string $jenis, string $status): \Closure
    {
        return function (Builder $query) use ($jenis, $status) {

            $query

                ->whereMonth('tanggal', $this->getBulan())

                ->whereYear('tanggal', $this->getTahun())

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

                        'kehadiranIbadah as dhuha_hadir' =>
                        $this->countIbadah('dhuha', 'hadir'),

                        'kehadiranIbadah as dhuha_menstruasi' =>
                        $this->countIbadah('dhuha', 'menstruasi'),

                        'kehadiranIbadah as dhuha_tidak_hadir' =>
                        $this->countIbadah('dhuha', 'tidak_hadir'),

                        'kehadiranIbadah as dzuhur_hadir' =>
                        $this->countIbadah('dzuhur', 'hadir'),

                        'kehadiranIbadah as dzuhur_menstruasi' =>
                        $this->countIbadah('dzuhur', 'menstruasi'),

                        'kehadiranIbadah as dzuhur_tidak_hadir' =>
                        $this->countIbadah('dzuhur', 'tidak_hadir'),

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

                            new RekapBulananIbadahExport(

                                $this->getBulan(),

                                $this->getTahun(),

                                $this->getKelasId(),

                            ),

                            'rekap-kehadiran-ibadah-bulanan-'
                                . now()->format('YmdHis')
                                . '.xlsx'

                        );
                    }),

            ])

            ->striped();
    }

    public function render(): View
    {
        return view('livewire.rekap-bulanan-ibadah-table');
    }
}
