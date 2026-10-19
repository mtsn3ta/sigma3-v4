<?php

namespace App\Livewire;

use App\Models\Kelas;
use App\Models\TugasGuruIzin;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class GuruBerhalanganForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public ?TugasGuruIzin $record = null;

    private const MODAL_ID = 'guru-berhalangan-modal';

    public function mount(): void
    {
        $this->resetForm();

        if (request()->query('action') === 'tambah') {
            $this->tambah();
        }
    }

    protected function resetForm(): void
    {
        $this->record = null;

        $this->form->fill([
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'jadwal_pilihan' => null,
            'kelas_id' => null,
            'mapel' => null,
            'jam_ke' => null,
            'isi_tugas' => null,
        ]);
    }

    #[On('tambah-guru-berhalangan')]
    public function tambah(): void
    {
        $this->resetForm();

        $this->dispatch(
            'open-modal',
            id: self::MODAL_ID
        );
    }

    #[On('edit-guru-berhalangan')]
    public function edit(int $id): void
    {
        $this->record = TugasGuruIzin::findOrFail($id);

        $this->form->fill([
            'tanggal' => $this->record->tanggal->toDateString(),
            'jadwal_pilihan' => null,
            'kelas_id' => $this->record->kelas_id,
            'mapel' => $this->record->mapel,
            'jam_ke' => $this->record->jam_ke,
            'isi_tugas' => $this->record->isi_tugas,
        ]);

        $this->dispatch(
            'open-modal',
            id: self::MODAL_ID
        );
    }

    public static function optionsJadwalGuru(?string $tanggal): array
    {
        if (blank($tanggal) || !Auth::user()?->pegawai) {
            return [];
        }

        $date = \Carbon\Carbon::parse($tanggal);
        $namaHari = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'][$date->format('l')] ?? '';

        $tahunAjaranId = \App\Models\TahunAjaran::aktifId();

        $jadwalList = \App\Models\JadwalPelajaran::with(['kelas', 'plotingGuru'])
            ->where('hari', $namaHari)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->whereHas('plotingGuru', fn($q) => $q->where('pegawai_id', Auth::user()->pegawai->id))
            ->get()
            ->sortBy(fn($j) => (int) $j->jam_ke)
            ->values();

        $options = [];
        foreach ($jadwalList as $jadwal) {
            $key = "{$jadwal->kelas_id}|{$jadwal->plotingGuru?->mapel}|{$jadwal->jam_ke}";
            $options[$key] = "Kelas {$jadwal->kelas?->nama_kelas} — {$jadwal->plotingGuru?->mapel} (Jam ke-{$jadwal->jam_ke})";
        }

        return $options;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->label('Tanggal Berhalangan')
                    ->default(now('Asia/Jakarta')->toDateString())
                    ->live()
                    ->native(false)
                    ->required(),

                Select::make('jadwal_pilihan')
                    ->label('Pilih dari Jadwal Mengajar Anda (Otomatis)')
                    ->placeholder('— Pilih jadwal untuk mengisi Kelas, Mapel & Jam otomatis —')
                    ->options(fn ($get) => self::optionsJadwalGuru($get('tanggal')))
                    ->live()
                    ->afterStateUpdated(function (?string $state, $set) {
                        if (filled($state)) {
                            $parts = explode('|', $state);
                            if (count($parts) === 3) {
                                $set('kelas_id', (int) $parts[0]);
                                $set('mapel', $parts[1]);
                                $set('jam_ke', $parts[2]);
                            }
                        }
                    })
                    ->dehydrated(false)
                    ->visible(fn ($get) => !empty(self::optionsJadwalGuru($get('tanggal')))),

                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(
                        Kelas::query()
                            ->orderBy('tingkat')
                            ->orderBy('nama_kelas')
                            ->pluck('nama_kelas', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('mapel')
                    ->label('Mata Pelajaran')
                    ->placeholder('Ketik atau pilih mapel Anda')
                    ->datalist(fn () => \App\Models\PlotingGuru::where('pegawai_id', Auth::user()?->pegawai?->id)
                        ->whereNotNull('mapel')
                        ->distinct()
                        ->pluck('mapel')
                        ->toArray()
                    )
                    ->required(),

                Select::make('jam_ke')
                    ->label('Jam Ke')
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '1-2' => '1 - 2',
                        '3-4' => '3 - 4',
                        '5-6' => '5 - 6',
                        '7-8' => '7 - 8',
                        '2-3' => '2 - 3',
                        '4-5' => '4 - 5',
                        '6-7' => '6 - 7',
                        '8-9' => '8 - 9',
                    ])
                    ->required(),

                Textarea::make('isi_tugas')
                    ->label('Isi / Instruksi Tugas Siswa')
                    ->placeholder('Tuliskan rincian tugas atau materi yang harus dikerjakan siswa selama jam pelajaran...')
                    ->rows(6)
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $isEdit = $this->record !== null;

        DB::transaction(function () use ($data) {
            if ($this->record) {
                $this->record->update([
                    'kelas_id' => $data['kelas_id'],
                    'tanggal' => $data['tanggal'],
                    'mapel' => $data['mapel'],
                    'jam_ke' => $data['jam_ke'],
                    'isi_tugas' => $data['isi_tugas'],
                ]);
            } else {
                TugasGuruIzin::create([
                    'pegawai_id' => Auth::user()->pegawai->id,
                    'kelas_id' => $data['kelas_id'],
                    'tanggal' => $data['tanggal'],
                    'mapel' => $data['mapel'],
                    'jam_ke' => $data['jam_ke'],
                    'isi_tugas' => $data['isi_tugas'],
                    'status' => 'baru',
                ]);
            }
        });

        Notification::make()
            ->title($isEdit ? 'Pengajuan berhasil diperbarui.' : 'Pengajuan berhasil dikirim.')
            ->success()
            ->send();

        $this->dispatch(
            'close-modal',
            id: self::MODAL_ID
        );

        $this->resetForm();

        $this->dispatch('refresh-riwayat-guru-berhalangan');
    }

    public function render(): View
    {
        return view('livewire.guru-berhalangan-form');
    }
}
