<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use App\Models\Kelas;
use App\Models\Pegawai;
use App\Models\PlotingGuru;
use App\Models\TahunAjaran;
use App\Models\JadwalPelajaran;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class KelolaJadwalPelajaran extends Page implements HasTable
{
    use InteractsWithTable;
    use WithFileUploads;

    protected static string $resource = JadwalPelajaranResource::class;
    protected string $view = 'filament.resources.jadwal-pelajarans.pages.kelola-jadwal-pelajaran';

    public ?int $kelasId = null;
    public ?\Illuminate\Support\Collection $daftarKelas = null;
    public ?int $tahunAjaranAktif = null;
    public array $jadwalData = [];

    public $csvFile = null;

    public ?string $selectedHari = null;
    public ?string $selectedJam = null;
    public ?int $selectedPlotingGuruId = null;
    public ?int $selectedJadwalId = null;

    public function mount(): void
    {
        $this->daftarKelas = Kelas::all();
        $this->kelasId = Kelas::first()?->id;
        $this->tahunAjaranAktif = TahunAjaran::where('aktif', true)->first()?->id;
        $this->loadJadwal();
    }

    public function updatedKelasId()
    {
        $this->loadJadwal();
    }

    public function loadJadwal()
    {
        if (!$this->kelasId || !$this->tahunAjaranAktif) return;

        $jadwals = JadwalPelajaran::with('plotingGuru.pegawai')
            ->where('kelas_id', $this->kelasId)
            ->where('tahun_ajaran_id', $this->tahunAjaranAktif)
            ->get();

        $this->jadwalData = [];
        foreach ($jadwals as $jadwal) {
            $this->jadwalData[$jadwal->hari][$jadwal->jam_ke] = [
                'id' => $jadwal->id,
                'ploting_guru_id' => $jadwal->ploting_guru_id,
                'kode_guru' => $jadwal->plotingGuru->kode_guru,
                'mapel'     => $jadwal->plotingGuru->mapel,
                'nama_guru' => $jadwal->plotingGuru->pegawai->nama,
            ];
        }
    }

    /**
     * Dipanggil dari wire:click pada sel jadwal. Menyiapkan state, lalu
     * membuka modal lewat event open-modal (bukan mountAction).
     */
    public function pilihSelJadwal(string $hari, string $jam): void
    {
        $existing = $this->jadwalData[$hari][$jam] ?? null;

        $this->selectedHari = $hari;
        $this->selectedJam = $jam;
        $this->selectedJadwalId = $existing['id'] ?? null;
        $this->selectedPlotingGuruId = $existing['ploting_guru_id'] ?? null;
        $this->resetErrorBag();

        $this->dispatch('open-modal', id: 'isi-jadwal');
    }

    /**
     * Daftar opsi guru & mapel untuk dropdown di modal.
     * Dipakai sebagai computed property: $this->plotingGuruOptions di Blade.
     */
    public function getPlotingGuruOptionsProperty()
    {
        return PlotingGuru::with('pegawai')
            ->where('tahun_ajaran_id', $this->tahunAjaranAktif)
            ->get()
            ->mapWithKeys(fn($plot) => [
                $plot->id => "{$plot->kode_guru} — {$plot->mapel} ({$plot->pegawai->nama})",
            ]);
    }

    public function simpanJadwal()
    {
        $this->validate([
            'selectedPlotingGuruId' => ['required', 'exists:ploting_gurus,id'],
        ], [
            'selectedPlotingGuruId.required' => 'Guru & Mata Pelajaran wajib dipilih.',
        ]);

        JadwalPelajaran::updateOrCreate(
            [
                'tahun_ajaran_id' => $this->tahunAjaranAktif,
                'kelas_id' => $this->kelasId,
                'hari' => $this->selectedHari,
                'jam_ke' => $this->selectedJam,
            ],
            [
                'ploting_guru_id' => $this->selectedPlotingGuruId,
            ]
        );

        $this->loadJadwal();
        $this->closeJadwalModal();

        Notification::make()
            ->title('Jadwal berhasil disimpan!')
            ->success()
            ->send();
    }

    public function hapusJadwal()
    {
        if ($this->selectedJadwalId) {
            JadwalPelajaran::where('id', $this->selectedJadwalId)->delete();
            $this->loadJadwal();

            Notification::make()
                ->title('Jadwal berhasil dihapus')
                ->success()
                ->send();
        }

        $this->closeJadwalModal();
    }

    public function closeJadwalModal()
    {
        $this->dispatch('close-modal', id: 'isi-jadwal');
        $this->reset(['selectedHari', 'selectedJam', 'selectedPlotingGuruId', 'selectedJadwalId']);
    }

    /**
     * Buka modal "Import CSV" — pola sama seperti pilihSelJadwal(), pakai
     * modal manual, bukan mountAction().
     */
    public function bukaImportModal(): void
    {
        $this->reset(['csvFile']);
        $this->resetErrorBag();
        $this->dispatch('open-modal', id: 'import-jadwal');
    }

    public function closeImportModal(): void
    {
        $this->dispatch('close-modal', id: 'import-jadwal');
        $this->reset(['csvFile']);
    }

    public function importCsv()
    {
        $this->validate([
            'csvFile' => ['required', 'file', 'mimes:csv,txt'],
        ], [], [
            'csvFile' => 'File CSV',
        ]);

        $file = $this->csvFile->getRealPath();
        $csv = array_map('str_getcsv', file($file));

        // 1. Kamus Mapel (Disamakan PERSIS dengan isi tabel ploting_gurus Anda)
        $mapelDict = [
            'QH' => 'QH',
            'AA' => 'AKIDAH AKHLAK',
            'FK' => 'FIKIH',
            'SKI' => 'SKI',
            'BA' => 'BAHASA ARAB',
            'PP' => 'PEND. PANCASILA',
            'BIN' => 'BAHASA INDONESIA',
            'MAT' => 'MATEMATIKA',
            'IPA' => 'IPA',
            'IPS' => 'IPS',
            'BING' => 'BAHASA INGGRIS',
            'PJOK' => 'PJOK',
            'INF' => 'INFORMATIKA',
            'SBK' => 'SBK',
            'PRA' => 'PRAKARYA',
            'BJ' => 'BAHASA JAWA',
            'BK' => 'BK',
        ];

        // 2. Pemetaan Index Kolom CSV ke Nama Kelas di Database
        $kolomKelas = [
            4 => '7A',
            5 => '7B',
            6 => '7C',
            7 => '7D',
            8 => '7E',
            9 => '7F',
            10 => '7G',
            11 => '7H',
            12 => '7I',
            13 => '7J',
            14 => '7K',
            15 => '8A',
            16 => '8B',
            17 => '8C',
            18 => '8D',
            19 => '8E',
            20 => '8F',
            21 => '8G',
            22 => '8H',
            23 => '8I',
            24 => '8J',
            25 => '8K',
            26 => '9A',
            27 => '9B',
            28 => '9C',
            29 => '9D',
            30 => '9E',
            31 => '9F',
            32 => '9G',
            33 => '9H',
            34 => '9I',
            35 => '9J',
            36 => '9K',
        ];

        $kelasIds = Kelas::pluck('id', 'nama_kelas')->toArray();
        $tahunAjaranId = TahunAjaran::aktifId();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $hariAktif = '';
            $berhasil = 0;

            foreach ($csv as $index => $row) {
                // Lewati baris judul (data baru mulai di baris ke-6 / index 5)
                if ($index < 5) continue;

                // Update hari jika di kolom 1 ada teks (Senin, Selasa, dst)
                if (!empty(trim($row[1] ?? ''))) {
                    $hariAktif = trim($row[1]);
                }

                $jamKe = trim($row[2] ?? '');
                // Lewati baris istirahat/upacara (karena bukan angka jam)
                if (empty($jamKe) || !is_numeric($jamKe)) continue;

                foreach ($kolomKelas as $colIdx => $namaKelas) {
                    if (!isset($row[$colIdx])) continue;

                    $isiSel = trim($row[$colIdx]);
                    if (empty($isiSel) || strtolower($isiSel) === 'kosong' || str_contains(strtolower($isiSel), 'istirahat')) continue;

                    // Pecah "34 PP" menjadi Kode = 34, Mapel Singkat = PP
                    $parts = explode(' ', $isiSel);
                    $kodeGuru = trim($parts[0]);
                    $singkatanMapel = isset($parts[1]) ? trim($parts[1]) : '';

                    $mapelFull = $mapelDict[$singkatanMapel] ?? $singkatanMapel;
                    $kelasId = $kelasIds[$namaKelas] ?? null;

                    if (!$kelasId) continue;

                    $ploting = PlotingGuru::where('tahun_ajaran_id', $tahunAjaranId)
                        ->where('kode_guru', $kodeGuru)
                        ->where('mapel', $mapelFull)
                        ->first();

                    if ($ploting) {
                        JadwalPelajaran::updateOrCreate([
                            'tahun_ajaran_id' => $tahunAjaranId,
                            'kelas_id' => $kelasId,
                            'hari' => $hariAktif,
                            'jam_ke' => $jamKe,
                        ], [
                            'ploting_guru_id' => $ploting->id,
                        ]);
                        $berhasil++;
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            $this->loadJadwal();
            $this->closeImportModal();

            Notification::make()
                ->title("Import selesai! {$berhasil} blok jadwal berhasil disimpan.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            Notification::make()
                ->title('Gagal: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pegawai::query()
                    ->where('jenis_pegawai', 'internal')
                    ->with(['ploting' => fn($query) => $query
                        ->where('tahun_ajaran_id', $this->tahunAjaranAktif)
                        ->orderBy('kode_guru')])
                    ->orderBy('nama')
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Guru')
                    ->searchable()
                    ->weight('bold')
                    ->icon('heroicon-o-user-circle')
                    ->description(fn(Pegawai $record) => $record->ploting->isEmpty()
                        ? 'Belum ada mapel diampu'
                        : $record->ploting->count() . ' mata pelajaran diampu'),

                TextColumn::make('ploting_info')
                    ->label('Kode & Mata Pelajaran')
                    ->getStateUsing(fn(Pegawai $record) => $record->ploting
                        ->map(fn($plot) => "{$plot->kode_guru} — {$plot->mapel}")
                        ->all())
                    ->badge()
                    ->color('primary')
                    ->wrap()
                    ->placeholder('— Belum diplotting —'),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('set_guru')
                        ->label('Tambah Kode & Mapel')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            TextInput::make('kode_guru')
                                ->label('Kode Guru')
                                ->helperText('Contoh: 01, 21, SI. Boleh dipakai berulang untuk mapel lain milik guru yang sama.')
                                ->required()
                                ->maxLength(10)
                                ->rule(fn(Pegawai $record) => Rule::unique('ploting_gurus', 'kode_guru')
                                    ->where('tahun_ajaran_id', $this->tahunAjaranAktif)
                                    ->whereNot('pegawai_id', $record->id))
                                ->validationMessages([
                                    'unique' => 'Kode guru ini sudah dipakai oleh guru lain pada tahun ajaran aktif.',
                                ]),
                            TextInput::make('mapel')
                                ->label('Mata Pelajaran')
                                ->required()
                                ->maxLength(100)
                                ->rule(fn(Pegawai $record, Get $get) => Rule::unique('ploting_gurus', 'mapel')
                                    ->where('tahun_ajaran_id', $this->tahunAjaranAktif)
                                    ->where('pegawai_id', $record->id)
                                    ->where('kode_guru', $get('kode_guru')))
                                ->validationMessages([
                                    'unique' => 'Guru ini sudah punya mapel yang sama dengan kode guru tersebut.',
                                ]),
                        ])
                        ->action(function (Pegawai $record, array $data) {
                            PlotingGuru::create([
                                'pegawai_id' => $record->id,
                                'tahun_ajaran_id' => $this->tahunAjaranAktif,
                                'kode_guru' => $data['kode_guru'],
                                'mapel' => $data['mapel'],
                            ]);
                        })
                        ->successNotificationTitle('Kode & mapel berhasil ditambahkan')
                        ->disabled(fn() => blank($this->tahunAjaranAktif))
                        ->tooltip(fn() => blank($this->tahunAjaranAktif) ? 'Tidak ada tahun ajaran aktif' : null),

                    Action::make('hapus_mapel')
                        ->label('Hapus Mapel')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->form([
                            Select::make('ploting_id')
                                ->label('Pilih mapel yang ingin dihapus')
                                ->options(fn(Pegawai $record) => $record->ploting
                                    ->mapWithKeys(fn($plot) => [$plot->id => "{$plot->kode_guru} — {$plot->mapel}"]))
                                ->required()
                                ->native(false),
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Ploting Guru')
                        ->modalDescription('Jadwal yang sudah memakai kode & mapel ini bisa terpengaruh. Lanjutkan?')
                        ->action(fn(array $data) => PlotingGuru::find($data['ploting_id'])?->delete())
                        ->successNotificationTitle('Mapel berhasil dihapus')
                        ->hidden(fn(Pegawai $record) => $record->ploting->isEmpty()),
                ])
                    ->label('Kelola')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->button()
                    ->color('gray'),
            ])
            ->defaultSort('nama')
            ->emptyStateHeading('Belum ada data guru')
            ->emptyStateDescription('Pastikan data pegawai dengan jenis internal sudah tersedia.')
            ->emptyStateIcon('heroicon-o-users');
    }
}
