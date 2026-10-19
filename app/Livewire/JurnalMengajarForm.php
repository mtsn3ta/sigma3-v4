<?php

namespace App\Livewire;

use App\Models\JurnalMengajar;
use App\Models\KehadiranSekolah;
use App\Models\Kelas;
use App\Models\AgendaMadrasah;
use App\Models\JadwalPelajaran;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\Siswa;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use App\Models\TahunAjaran;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Carbon\Carbon;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class JurnalMengajarForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];
    public ?JurnalMengajar $record = null;
    public Collection $siswaTidakHadir;
    public Collection $daftarSiswa;

    public bool $isLocked = false;
    public ?string $lockMessage = null;

    public function mount(): void
    {
        $this->form->fill([
            'tanggal' => now('Asia/Jakarta')->toDateString(),
        ]);

        $this->siswaTidakHadir = collect();
        $this->daftarSiswa = collect();

        if (request()->query('action') === 'tambah') {
            $this->tambah();
        }
    }

    #[On('tambah-jurnal')]
    public function tambah(): void
    {
        $this->record = null;
        $tanggalHariIni = now('Asia/Jakarta')->toDateString();

        $this->form->fill([
            'tanggal' => $tanggalHariIni,
            'jadwal_pilihan' => null,
            'kelas_id' => null,
            'jam_ke' => null,
            'mapel' => null,
            'materi' => null,
            'catatan' => null,
            'kehadiran_pembelajaran' => [],
        ]);

        $this->siswaTidakHadir = collect();
        $this->daftarSiswa = collect();

        $this->cekAgendaDanJadwal($tanggalHariIni);
        $this->dispatch('open-modal', id: 'jurnal-modal');
    }

    #[On('edit-jurnal-mengajar')]
    public function edit(int $id): void
    {
        $this->record = JurnalMengajar::with('kehadiranPembelajaran')->findOrFail($id);

        $this->form->fill([
            'tanggal' => $this->record->tanggal->toDateString(),
            'jadwal_pilihan' => null,
            'kelas_id' => $this->record->kelas_id,
            'jam_ke' => $this->record->jam_ke,
            'mapel' => $this->record->mapel,
            'materi' => $this->record->materi,
            'catatan' => $this->record->catatan,
            'dokumentasi_foto' => $this->record->dokumentasi_foto,
            'dokumentasi_video' => $this->record->dokumentasi_video,
            'kehadiran_pembelajaran' => $this->record->kehadiranPembelajaran->map(fn($item) => [
                'siswa_id' => $item->siswa_id,
                'status' => $item->status,
                'keterangan' => $item->keterangan,
            ])->toArray(),
        ]);

        $this->loadKehadiranSekolah();
        $this->cekAgendaDanJadwal($this->record->tanggal->toDateString(), $this->record->kelas_id, true);
        $this->dispatch('open-modal', id: 'jurnal-modal');
    }

    /**
     * BARU: Ambil jadwal guru hari ini, lalu GABUNGKAN jam-jam yang berurutan
     * jika kelas dan mapelnya sama. Dipakai bareng oleh:
     * - dropdown "Pilih Jadwal Anda Hari Ini"
     * - logika auto-isi ketika guru cuma punya 1 jadwal (kini 1 GRUP jadwal)
     *
     * Return: array of groups, masing-masing:
     * [
     *   'ids' => [12, 13],
     *   'kelas_id' => 5,
     *   'kelas_nama' => '8B',
     *   'mapel' => 'PJOK',
     *   'jam_awal' => 1,
     *   'jam_akhir' => 2,
     *   'jam_ke_gabung' => '1-2', // atau '1' kalau cuma 1 jam
     * ]
     */
    private function jadwalTergabungHariIni(string $tanggal): array
    {
        $date = Carbon::parse($tanggal);
        $namaHari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][$date->format('l')];

        if (!Auth::user()?->pegawai) {
            return [];
        }

        $jadwalHariIni = JadwalPelajaran::with(['kelas', 'plotingGuru'])
            ->where('hari', $namaHari)
            ->where('tahun_ajaran_id', TahunAjaran::aktifId())
            ->whereHas('plotingGuru', fn($q) => $q->where('pegawai_id', Auth::user()->pegawai->id))
            ->get()
            ->sortBy(fn($j) => (int) $j->jam_ke)
            ->values();

        $groups = [];
        $current = null;

        foreach ($jadwalHariIni as $jadwal) {
            $jamInt = (int) $jadwal->jam_ke;
            $mapel = $jadwal->plotingGuru->mapel;

            $tersambung = $current
                && $current['kelas_id'] === $jadwal->kelas_id
                && $current['mapel'] === $mapel
                && $jamInt === $current['jam_akhir'] + 1;

            if ($tersambung) {
                // Jam ini nyambung ke grup sebelumnya (kelas + mapel sama, jam berurutan)
                $current['ids'][] = $jadwal->id;
                $current['jam_akhir'] = $jamInt;
            } else {
                if ($current) {
                    $groups[] = $current;
                }
                $current = [
                    'ids' => [$jadwal->id],
                    'kelas_id' => $jadwal->kelas_id,
                    'kelas_nama' => $jadwal->kelas->nama_kelas,
                    'mapel' => $mapel,
                    'jam_awal' => $jamInt,
                    'jam_akhir' => $jamInt,
                ];
            }
        }

        if ($current) {
            $groups[] = $current;
        }

        // Susun jam_ke_gabung: "1" kalau cuma 1 jam, "1-2" kalau berurutan
        foreach ($groups as &$g) {
            $g['jam_ke_gabung'] = $g['jam_awal'] === $g['jam_akhir']
                ? (string) $g['jam_awal']
                : "{$g['jam_awal']}-{$g['jam_akhir']}";
        }
        unset($g);

        return $groups;
    }

    public function cekAgendaDanJadwal(string $tanggal, ?int $kelasId = null, bool $isEditMode = false): void
    {
        if (blank($tanggal)) return;

        $this->isLocked = false;
        $this->lockMessage = null;
        $date = Carbon::parse($tanggal);

        // LAPIS 1: Hari Minggu
        if ($date->isSunday()) {
            $this->isLocked = true;
            $this->lockMessage = "Libur Akhir Pekan.";
            $this->kosongkanForm();
            return;
        }

        // LAPIS 2 & 3: Agenda Madrasah
        if (class_exists(AgendaMadrasah::class)) {
            $agendaPusat = AgendaMadrasah::whereDate('tanggal', $tanggal)
                ->whereIn('tipe_agenda', ['libur_nasional', 'kegiatan_bersama'])
                ->first();

            if ($agendaPusat) {
                $this->isLocked = true;
                $this->lockMessage = "Terkunci oleh Agenda: " . $agendaPusat->nama_agenda;
                $this->kosongkanForm($agendaPusat->nama_agenda, $agendaPusat->keterangan);
                return;
            }

            if ($kelasId) {
                $agendaKelas = AgendaMadrasah::whereDate('tanggal', $tanggal)
                    ->where('tipe_agenda', 'kondisi_kelas')
                    ->where('kelas_id', $kelasId)
                    ->first();

                if ($agendaKelas) {
                    $this->isLocked = true;
                    $this->lockMessage = "Kelas ini sedang mengikuti agenda: " . $agendaKelas->nama_agenda;
                    $this->data['materi'] = $agendaKelas->nama_agenda;
                    $this->data['catatan'] = $agendaKelas->keterangan ?? 'Siswa mengikuti kegiatan khusus.';
                    return;
                }
            }
        }

        // LAPIS 4: Eksekusi Jadwal Pintar (PAKAI JADWAL YANG SUDAH DIGABUNG)
        if (!$isEditMode && !$this->isLocked && Auth::user()?->pegawai) {

            $groups = $this->jadwalTergabungHariIni($tanggal);

            // JIKA GURU HANYA PUNYA 1 GRUP JADWAL (bukan 1 baris mentah) DI HARI ITU,
            // LANGSUNG ISI SEMUA OTOMATIS — termasuk jam_ke gabungan seperti "1-2"!
            if (count($groups) === 1) {
                $grup = $groups[0];
                $jadwalPertama = JadwalPelajaran::with('plotingGuru')->find($grup['ids'][0]);

                $this->data['jadwal_pilihan'] = implode(',', $grup['ids']);
                $this->data['kelas_id'] = $grup['kelas_id'];
                $this->data['jam_ke'] = $grup['jam_ke_gabung'];
                $this->data['mapel'] = $grup['mapel'];

                $this->loadKehadiranSekolah();
            }
            // Jika lebih dari 1 grup, biarkan kosong agar guru memilih dari Dropdown Ajaib di Form.
        }
    }

    private function kosongkanForm($materiDefault = '-', $catatanDefault = null)
    {
        $this->data['jadwal_pilihan'] = null;
        $this->data['kelas_id'] = null;
        $this->data['jam_ke'] = null;
        $this->data['mapel'] = null;
        $this->data['materi'] = $materiDefault;
        $this->data['catatan'] = $catatanDefault;
        $this->data['kehadiran_pembelajaran'] = [];
    }

    protected function loadKehadiranSekolah(): void
    {
        $tanggal = $this->data['tanggal'] ?? null;
        $kelasId = $this->data['kelas_id'] ?? null;

        if (blank($tanggal) || blank($kelasId)) {
            $this->siswaTidakHadir = collect();
            $this->daftarSiswa = collect();
            return;
        }

        $tahunAjaranId = TahunAjaran::aktifId();

        $this->siswaTidakHadir = KehadiranSekolah::query()
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['izin', 'sakit', 'alfa'])
            ->whereHas('siswa', fn ($query) => $query->where('status', 'aktif'))
            ->whereHas('siswa.kelasSiswa', function ($query) use ($kelasId, $tahunAjaranId) {
                $query->where('kelas_id', $kelasId);
                if ($tahunAjaranId) $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->with('siswa')
            ->orderBy('status')
            ->get();

        $this->daftarSiswa = Siswa::query()
            ->where('status', 'aktif')
            ->whereHas('kelasSiswa', function ($query) use ($kelasId, $tahunAjaranId) {
                $query->where('kelas_id', $kelasId);
                if ($tahunAjaranId) $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->whereHas('kehadiranSekolah', function ($query) use ($tanggal) {
                $query->whereDate('tanggal', $tanggal)->whereIn('status', ['hadir', 'terlambat']);
            })
            ->orderBy('nama')
            ->get();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Langkah 1 — Tanggal & Jadwal Mengajar')
                    ->description('Pilih tanggal dulu. Jika jadwal Anda hari itu cuma satu (termasuk jam berurutan yang digabung otomatis), sistem akan mengisi otomatis.')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal Mengajar')
                            ->default(now('Asia/Jakarta')->toDateString())
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->prefixIcon('heroicon-o-calendar')
                            ->extraInputAttributes(['style' => 'font-size: 1.1rem; padding: 0.85rem 1rem;'])
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get) {
                                $this->cekAgendaDanJadwal($state, $get('kelas_id'));
                                $this->loadKehadiranSekolah();
                            })
                            ->required(),

                        Select::make('jadwal_pilihan')
                            ->label('Pilih Jadwal Anda Hari Ini')
                            ->placeholder('— Klik untuk memilih jadwal —')
                            ->native(false)
                            ->prefixIcon('heroicon-o-clipboard-document-list')
                            ->extraAttributes(['style' => 'font-size: 1.1rem;'])
                            ->options(function (Get $get) {
                                $tanggal = $get('tanggal');
                                if (blank($tanggal)) return [];

                                $groups = $this->jadwalTergabungHariIni($tanggal);

                                $options = [];
                                foreach ($groups as $g) {
                                    // value = gabungan id jadwal, dipisah koma (misal "12,13")
                                    $value = implode(',', $g['ids']);

                                    $labelJam = $g['jam_awal'] === $g['jam_akhir']
                                        ? "Jam ke {$g['jam_awal']}"
                                        : "Jam ke {$g['jam_awal']}–{$g['jam_akhir']}"; // digabung karena kelas & mapel sama

                                    $options[$value] = "Kelas {$g['kelas_nama']} — {$labelJam} ({$g['mapel']})";
                                }

                                return $options;
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if (!$state) return;

                                $ids = explode(',', $state);

                                $jadwalList = JadwalPelajaran::with('plotingGuru')
                                    ->whereIn('id', $ids)
                                    ->get()
                                    ->sortBy(fn($j) => (int) $j->jam_ke)
                                    ->values();

                                if ($jadwalList->isEmpty()) return;

                                $pertama = $jadwalList->first();
                                $terakhir = $jadwalList->last();

                                $set('kelas_id', $pertama->kelas_id);
                                $set('mapel', $pertama->plotingGuru->mapel);

                                // Kalau cuma 1 id -> jam_ke tunggal. Kalau lebih -> gabungan "awal-akhir".
                                $jamKe = $pertama->jam_ke === $terakhir->jam_ke
                                    ? $pertama->jam_ke
                                    : "{$pertama->jam_ke}-{$terakhir->jam_ke}";

                                $set('jam_ke', $jamKe);

                                $this->loadKehadiranSekolah();
                            })
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->helperText('Jam yang berurutan dengan kelas & mapel sama otomatis digabung jadi satu jurnal. Kalau jadwal Anda cuma satu hari ini, kolom di bawah sudah otomatis terisi.'),
                    ])
                    ->visible(fn() => !$this->isLocked),

                Section::make('Jurnal Tidak Bisa Diisi')
                    ->icon('heroicon-o-lock-closed')
                    ->iconColor('danger')
                    ->description('Tidak perlu mengisi apa pun untuk tanggal ini.')
                    ->schema([
                        TextInput::make('materi')
                            ->label('Keterangan')
                            ->disabled()
                            ->extraInputAttributes(['style' => 'font-size: 1.1rem; padding: 0.85rem 1rem;'])
                            ->dehydrated(false),
                    ])
                    ->visible(fn() => $this->isLocked),

                Section::make('Langkah 2 — Detail Kelas & Pelajaran')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-user-group')
                                    ->extraAttributes(['style' => 'font-size: 1.1rem;'])
                                    ->options(Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->pluck('nama_kelas', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Get $get) {
                                        $this->cekAgendaDanJadwal($get('tanggal'), $state);
                                        $this->loadKehadiranSekolah();
                                    })
                                    ->required(),

                                // DIUBAH: options sekarang dinamis, bisa menampilkan hasil gabungan
                                // seperti "1-2", "1-3" dst yang tidak ada di daftar statis lama.
                                Select::make('jam_ke')
                                    ->label('Jam Pelajaran Ke')
                                    ->native(false)
                                    ->prefixIcon('heroicon-o-clock')
                                    ->extraAttributes(['style' => 'font-size: 1.1rem;'])
                                    ->options(function (Get $get) {
                                        $statis = [
                                            '1' => 'Jam ke-1',
                                            '2' => 'Jam ke-2',
                                            '3' => 'Jam ke-3',
                                            '4' => 'Jam ke-4',
                                            '5' => 'Jam ke-5',
                                            '6' => 'Jam ke-6',
                                            '7' => 'Jam ke-7',
                                            '8' => 'Jam ke-8',
                                            '9' => 'Jam ke-9',
                                            '1-2' => 'Jam ke-1 sampai 2',
                                            '3-4' => 'Jam ke-3 sampai 4',
                                            '5-6' => 'Jam ke-5 sampai 6',
                                            '7-8' => 'Jam ke-7 sampai 8',
                                            '2-3' => 'Jam ke-2 sampai 3',
                                            '4-5' => 'Jam ke-4 sampai 5',
                                            '6-7' => 'Jam ke-6 sampai 7',
                                            '8-9' => 'Jam ke-8 sampai 9',
                                        ];

                                        // Kalau nilai saat ini hasil gabungan otomatis (misal "1-3")
                                        // dan belum ada di daftar statis, tambahkan biar tetap tampil rapi.
                                        $nilaiSaatIni = $get('jam_ke');
                                        if ($nilaiSaatIni && !array_key_exists($nilaiSaatIni, $statis)) {
                                            if (str_contains($nilaiSaatIni, '-')) {
                                                [$awal, $akhir] = explode('-', $nilaiSaatIni);
                                                $statis[$nilaiSaatIni] = "Jam ke-{$awal} sampai {$akhir}";
                                            } else {
                                                $statis[$nilaiSaatIni] = "Jam ke-{$nilaiSaatIni}";
                                            }
                                        }

                                        return $statis;
                                    })
                                    ->required(),

                                TextInput::make('mapel')
                                    ->label('Mata Pelajaran')
                                    ->prefixIcon('heroicon-o-book-open')
                                    ->extraInputAttributes(['style' => 'font-size: 1.1rem; padding: 0.85rem 1rem;'])
                                    ->required(),
                            ]),
                    ])
                    ->visible(fn() => !$this->isLocked),

                Section::make('Langkah 3 — Materi yang Diajarkan')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        Textarea::make('materi')
                            ->label('Tulis materi yang diajarkan hari ini')
                            ->placeholder('Contoh: Membahas Bab 3 tentang Pecahan, latihan soal halaman 20-22')
                            ->rows(4)
                            ->extraInputAttributes(['style' => 'font-size: 1.1rem; padding: 0.85rem 1rem;'])
                            ->required(),
                    ])
                    ->visible(fn() => !$this->isLocked),

                Section::make('Langkah 4 — Siswa yang Tidak Ikut Pelajaran (Jika Ada)')
                    ->description('Lewati langkah ini jika semua siswa mengikuti pelajaran dengan baik.')
                    ->icon('heroicon-o-user-minus')
                    ->collapsible()
                    ->collapsed(fn() => empty($this->data['kehadiran_pembelajaran']))
                    ->schema([
                        Repeater::make('kehadiran_pembelajaran')
                            ->label('')
                            ->schema([
                                Select::make('siswa_id')
                                    ->label('Nama Siswa')
                                    ->native(false)
                                    ->extraAttributes(['style' => 'font-size: 1.05rem;'])
                                    ->options(function (Get $get) {
                                        $kelasId = $get('../../kelas_id');
                                        $tanggal = $get('../../tanggal');
                                        if (blank($kelasId) || blank($tanggal)) return [];

                                        $tahunAjaranId = TahunAjaran::aktifId();
                                        return Siswa::query()
                                            ->where('status', 'aktif')
                                            ->whereHas('kelasSiswa', function ($query) use ($kelasId, $tahunAjaranId) {
                                                $query->where('kelas_id', $kelasId);
                                                if ($tahunAjaranId) $query->where('tahun_ajaran_id', $tahunAjaranId);
                                            })
                                            ->whereHas('kehadiranSekolah', function ($query) use ($tanggal) {
                                                $query->whereDate('tanggal', $tanggal)->whereIn('status', ['hadir', 'terlambat']);
                                            })
                                            ->orderBy('nama')
                                            ->pluck('nama', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpanFull(),

                                Select::make('status')
                                    ->label('Alasan Tidak Ikut')
                                    ->native(false)
                                    ->extraAttributes(['style' => 'font-size: 1.05rem;'])
                                    ->options([
                                        'bolos' => 'Bolos',
                                        'ke_bk' => 'Dipanggil ke BK',
                                        'ke_uks' => 'Sakit / ke UKS',
                                        'dispensasi' => 'Dispensasi (Izin Kegiatan)',
                                        'lomba' => 'Mengikuti Lomba',
                                        'lainnya' => 'Alasan Lain',
                                    ])
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('keterangan')
                                    ->label('Keterangan Tambahan (Opsional)')
                                    ->extraInputAttributes(['style' => 'font-size: 1.05rem; padding: 0.7rem 1rem;'])
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->columns(1)
                            ->itemLabel(fn(array $state): ?string =>
                            !empty($state['siswa_id'])
                                ? (Siswa::find($state['siswa_id'])?->nama ?? 'Siswa')
                                : 'Siswa Baru')
                            ->addActionLabel('+ Tambah Siswa yang Tidak Ikut')
                            ->deleteAction(
                                fn($action) => $action->requiresConfirmation()->modalHeading('Hapus data siswa ini?')
                            ),
                    ])
                    ->visible(fn() => !$this->isLocked),

                Section::make('Catatan Tambahan (Opsional)')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Textarea::make('catatan')
                            ->label('')
                            ->placeholder('Catatan lain seputar KBM hari ini, jika ada...')
                            ->rows(3)
                            ->extraInputAttributes(['style' => 'font-size: 1.1rem; padding: 0.85rem 1rem;']),
                    ])
                    ->visible(fn() => !$this->isLocked),

                Section::make('Dokumentasi KBM (Foto / Video)')
                    ->icon('heroicon-o-camera')
                    ->collapsible()
                    ->schema([
                        FileUpload::make('dokumentasi_foto')
                            ->label('Foto Kegiatan KBM')
                            ->image()
                            ->disk('public')
                            ->directory('jurnal/foto')
                            ->maxSize(5120),
                        FileUpload::make('dokumentasi_video')
                            ->label('Video Kegiatan KBM (Opsional)')
                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-m4v'])
                            ->disk('public')
                            ->directory('jurnal/video')
                            ->maxSize(20480),
                    ])
                    ->visible(fn() => !$this->isLocked),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        if ($this->isLocked) {
            Notification::make()->title('Form Terkunci')->danger()->send();
            return;
        }

        $data = $this->form->getState();
        $isEdit = $this->record !== null;

        DB::transaction(function () use ($data) {
            if ($this->record) {
                $this->record->update([
                    'kelas_id' => $data['kelas_id'],
                    'tanggal' => $data['tanggal'],
                    'jam_ke' => $data['jam_ke'],
                    'mapel' => $data['mapel'],
                    'materi' => $data['materi'],
                    'catatan' => $data['catatan'],
                    'dokumentasi_foto' => $data['dokumentasi_foto'] ?? null,
                    'dokumentasi_video' => $data['dokumentasi_video'] ?? null,
                ]);
                $jurnal = $this->record;
            } else {
                $jurnal = JurnalMengajar::create([
                    'pegawai_id' => Auth::user()->pegawai->id,
                    'kelas_id' => $data['kelas_id'],
                    'tanggal' => $data['tanggal'],
                    'jam_ke' => $data['jam_ke'],
                    'mapel' => $data['mapel'],
                    'materi' => $data['materi'],
                    'catatan' => $data['catatan'],
                    'dokumentasi_foto' => $data['dokumentasi_foto'] ?? null,
                    'dokumentasi_video' => $data['dokumentasi_video'] ?? null,
                ]);
            }

            $jurnal->kehadiranPembelajaran()->delete();
            foreach ($data['kehadiran_pembelajaran'] ?? [] as $item) {
                $jurnal->kehadiranPembelajaran()->create([
                    'siswa_id' => $item['siswa_id'],
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'],
                ]);
            }
        });

        Notification::make()
            ->title($isEdit ? 'Jurnal diperbarui.' : 'Jurnal berhasil disimpan.')
            ->success()
            ->send();

        $this->dispatch('close-modal', id: 'jurnal-modal');
        $this->dispatch('refresh-riwayat-jurnal');
    }

    public function render(): View
    {
        return view('livewire.jurnal-mengajar-form');
    }
}
