<?php

namespace App\Livewire;

use App\Filament\Actions\BeriUmpanBalikAction;
use App\Models\DaftarNilai;
use App\Models\JurnalMengajar;
use App\Models\Pegawai;
use App\Models\PerangkatPembelajaran;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MonitoringUploadGuruTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /**
     * Rentang periode dari filter tabel. Default: bulan berjalan.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function periode(): array
    {
        $state = $this->getTableFilterState('periode') ?? [];

        $dari = ! empty($state['dari'])
            ? Carbon::parse($state['dari'], 'Asia/Jakarta')->startOfDay()
            : now('Asia/Jakarta')->startOfMonth();

        $sampai = ! empty($state['sampai'])
            ? Carbon::parse($state['sampai'], 'Asia/Jakarta')->endOfDay()
            : now('Asia/Jakarta')->endOfMonth();

        return [$dari, $sampai];
    }

    public function table(Table $table): Table
    {
        [$dari, $sampai] = $this->periode();

        return $table
            ->query(
                Pegawai::query()
                    ->where('jenis_pegawai', 'internal')
                    ->where('status', 'aktif')
                    ->whereHas('user.roles', fn ($query) => $query->where('name', 'guru'))
                    ->with('user')
                    ->withCount([
                        'perangkatPembelajaran as perangkat_count' => fn ($query) => $query->whereBetween('created_at', [$dari, $sampai]),
                        'jurnalMengajar as jurnal_count'           => fn ($query) => $query->whereBetween('created_at', [$dari, $sampai]),
                        'daftarNilai as nilai_count'               => fn ($query) => $query->whereBetween('created_at', [$dari, $sampai]),
                        'jurnalMengajar as dokumentasi_count'      => fn ($query) => $query->whereBetween('created_at', [$dari, $sampai])
                            ->where(function ($query) {
                                $query->whereNotNull('dokumentasi_foto')
                                    ->orWhereNotNull('dokumentasi_video');
                            }),
                    ])
                    ->withMax('perangkatPembelajaran as perangkat_terakhir', 'created_at')
                    ->withMax('jurnalMengajar as jurnal_terakhir', 'created_at')
                    ->withMax('daftarNilai as nilai_terakhir', 'created_at')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Guru')
                    ->icon('heroicon-m-user-circle')
                    ->iconColor('primary')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Pegawai $record): string => $record->nik_nip),

                Tables\Columns\TextColumn::make('perangkat_count')
                    ->label('Perangkat')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'danger')
                    ->icon(fn ($state): string => $state > 0 ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jurnal_count')
                    ->label('Jurnal')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'danger')
                    ->icon(fn ($state): string => $state > 0 ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai_count')
                    ->label('Daftar Nilai')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'danger')
                    ->icon(fn ($state): string => $state > 0 ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dokumentasi_count')
                    ->label('Dok. KBM')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'warning')
                    ->icon('heroicon-m-camera')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_kelengkapan')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Pegawai $record): string {
                        $jumlah = collect([
                            $record->perangkat_count,
                            $record->jurnal_count,
                            $record->nilai_count,
                        ])->filter(fn ($nilai) => $nilai > 0)->count();

                        return match (true) {
                            $jumlah === 0 => 'Belum Upload',
                            $jumlah === 3 => 'Lengkap',
                            default       => 'Sebagian',
                        };
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Lengkap'      => 'success',
                        'Sebagian'     => 'warning',
                        default        => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Lengkap'      => 'heroicon-m-check-badge',
                        'Sebagian'     => 'heroicon-m-clock',
                        default        => 'heroicon-m-exclamation-triangle',
                    }),

                Tables\Columns\TextColumn::make('aktivitas_terakhir')
                    ->label('Aktivitas Terakhir')
                    ->getStateUsing(function (Pegawai $record): ?string {
                        $tanggal = collect([
                            $record->perangkat_terakhir,
                            $record->jurnal_terakhir,
                            $record->nilai_terakhir,
                        ])->filter()->max();

                        return $tanggal
                            ? Carbon::parse($tanggal)->locale('id')->diffForHumans()
                            : null;
                    })
                    ->placeholder('Belum ada')
                    ->alignEnd(),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
                    ->label('Periode')
                    ->form([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->default(now('Asia/Jakarta')->startOfMonth()->toDateString())
                            ->native(false),
                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->default(now('Asia/Jakarta')->endOfMonth()->toDateString())
                            ->native(false),
                    ])
                    ->indicateUsing(function (array $data): array {
                        $indikator = [];

                        if ($data['dari'] ?? null) {
                            $indikator[] = 'Dari: ' . Carbon::parse($data['dari'])->translatedFormat('d M Y');
                        }

                        if ($data['sampai'] ?? null) {
                            $indikator[] = 'Sampai: ' . Carbon::parse($data['sampai'])->translatedFormat('d M Y');
                        }

                        return $indikator;
                    }),

                Tables\Filters\Filter::make('belum_lengkap')
                    ->label('Hanya yang Belum Lengkap')
                    ->toggle()
                    ->query(fn ($query) => $query->where(function ($query) {
                        $query->whereDoesntHave('perangkatPembelajaran')
                            ->orWhereDoesntHave('jurnalMengajar')
                            ->orWhereDoesntHave('daftarNilai');
                    })),
            ])
            ->filtersFormColumns(2)
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat Unggahan')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->button()
                    ->modalHeading(fn (Pegawai $record): string => 'Unggahan ' . $record->nama)
                    ->modalContent(fn (Pegawai $record) => view('filament.modals.monitoring-upload-guru-detail', [
                        'pegawai'    => $record,
                        'perangkat'  => PerangkatPembelajaran::query()->where('pegawai_id', $record->id)->latest()->limit(5)->get(),
                        'daftarNilai' => DaftarNilai::query()
                            ->where('pegawai_id', $record->id)
                            ->withCount('nilaiSiswa')
                            ->withAvg('nilaiSiswa', 'nilai')
                            ->latest()
                            ->limit(5)
                            ->get(),
                        'jurnal'     => JurnalMengajar::query()->where('pegawai_id', $record->id)->latest('tanggal')->limit(5)->get(),
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                BeriUmpanBalikAction::make(pilihKategori: true),
            ])
            ->toolbarActions([])
            ->emptyStateHeading('Belum Ada Data Guru')
            ->emptyStateDescription('Data guru akan tampil di sini setelah akun guru terdaftar.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultSort('nama');
    }

    public function render(): View
    {
        return view('livewire.monitoring-upload-guru-table');
    }
}
