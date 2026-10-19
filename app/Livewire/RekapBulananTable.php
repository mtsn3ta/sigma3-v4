<?php

namespace App\Livewire;

use App\Models\Siswa;
use App\Models\Kelas;
// HAPUS: use Filament\Actions\Action;
use Filament\Actions\Action; // WAJIB GUNAKAN INI UNTUK TABEL
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
use Livewire\Component;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section; // Tambahkan ini
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KehadiranSekolah\RekapBulananExport;

class RekapBulananTable extends Component implements HasActions, HasForms, HasTable
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

    public function table(Table $table): Table
    {
        // Array pembantu untuk menampilkan nama bulan di deskripsi tabel
        $namaBulan = [
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
            12 => 'Desember'
        ];

        return $table
            ->heading('Rekap Kehadiran Bulanan')
            ->description('Periode: ' . $namaBulan[$this->getBulan()] . ' ' . $this->getTahun())
            ->query(
                Siswa::query()
                    ->where('status', 'aktif')
                    ->when(
                        $this->getKelasId(),
                        fn(Builder $query, $kelasId) =>
                        $query->whereHas(
                            'kelasAktif',
                            fn(Builder $query) => $query->where('kelas_id', $kelasId)
                        )
                    )
                    ->with(['kelasAktif.kelas'])
                    ->withCount([
                        'kehadiranSekolah as hadir_count' => fn(Builder $query) =>
                        $query->whereMonth('tanggal', $this->getBulan())
                            ->whereYear('tanggal', $this->getTahun())
                            ->where('status', 'hadir'),

                        'kehadiranSekolah as terlambat_count' => fn(Builder $query) =>
                        $query->whereMonth('tanggal', $this->getBulan())
                            ->whereYear('tanggal', $this->getTahun())
                            ->where('status', 'terlambat'),

                        'kehadiranSekolah as izin_count' => fn(Builder $query) =>
                        $query->whereMonth('tanggal', $this->getBulan())
                            ->whereYear('tanggal', $this->getTahun())
                            ->where('status', 'izin'),

                        'kehadiranSekolah as sakit_count' => fn(Builder $query) =>
                        $query->whereMonth('tanggal', $this->getBulan())
                            ->whereYear('tanggal', $this->getTahun())
                            ->where('status', 'sakit'),

                        'kehadiranSekolah as alfa_count' => fn(Builder $query) =>
                        $query->whereMonth('tanggal', $this->getBulan())
                            ->whereYear('tanggal', $this->getTahun())
                            ->where('status', 'alfa'),
                    ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->weight('bold') // Ditebalkan agar menjadi fokus utama
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelasAktif.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('hadir_count')
                    ->label('Hadir')
                    ->badge()->color('success')->alignCenter(),

                Tables\Columns\TextColumn::make('terlambat_count')
                    ->label('Terlambat')
                    ->badge()->color('warning')->alignCenter(),

                Tables\Columns\TextColumn::make('izin_count')
                    ->label('Izin')
                    ->badge()->color('info')->alignCenter(),

                Tables\Columns\TextColumn::make('sakit_count')
                    ->label('Sakit')
                    ->badge()->color('gray')->alignCenter(),

                Tables\Columns\TextColumn::make('alfa_count')
                    ->label('Alfa')
                    ->badge()->color('danger')->alignCenter(),

                Tables\Columns\TextColumn::make('persentase')
                    ->label('Kehadiran (%)')
                    ->alignCenter() // Pastikan persentase juga di tengah
                    ->state(function (Siswa $record) {
                        $totalHari = 25; // Anggap 25 hari aktif sebulan
                        return $totalHari > 0 ? round(($record->hadir_count / $totalHari) * 100) . '%' : '0%';
                    })
                    ->badge()
                    ->color(fn($state) => (int)$state >= 80 ? 'success' : 'danger'),
            ])
            ->headerActions([
                // Ini sekarang sudah aman karena import-nya benar
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(
                            new RekapBulananExport(
                                $this->getBulan(),
                                $this->getTahun(),
                                $this->getKelasId(),
                            ),
                            'rekap-kehadiran-bulanan-' . now()->format('YmdHis') . '.xlsx'
                        );
                    }),
            ])
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.rekap-bulanan-table');
    }
}
