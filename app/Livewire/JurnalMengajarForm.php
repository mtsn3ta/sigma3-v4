<?php

namespace App\Livewire;

use App\Models\JurnalMengajar;
use App\Models\KehadiranSekolah;
use App\Models\Kelas;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
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

class JurnalMengajarForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];
    public ?JurnalMengajar $record = null;
    public Collection $siswaTidakHadir;
    public Collection $daftarSiswa;

    public function mount(): void
    {
        $this->form->fill([
            'tanggal' => now()->toDateString(),
        ]);
        $this->siswaTidakHadir = collect();
        $this->daftarSiswa = collect();
    }

    // Tambahkan Listener ini untuk menangani klik tombol "Isi Jurnal"
    #[On('tambah-jurnal')]
    public function tambah(): void
    {
        $this->record = null;
        $this->form->fill([
            'tanggal' => now()->toDateString(),
            'kelas_id' => null,
            'jam_ke' => null,
            'mapel' => null,
            'materi' => null,
            'catatan' => null,
            'kehadiran_pembelajaran' => [],
        ]);
        $this->siswaTidakHadir = collect();
        $this->daftarSiswa = collect();

        // Perintah untuk membuka modal di sisi browser
        $this->dispatch('open-modal', id: 'jurnal-modal');
    }

    #[On('edit-jurnal-mengajar')]
    public function edit(int $id): void
    {
        $this->record = JurnalMengajar::with('kehadiranPembelajaran')->findOrFail($id);

        $this->form->fill([
            'tanggal' => $this->record->tanggal->toDateString(),
            'kelas_id' => $this->record->kelas_id,
            'jam_ke' => $this->record->jam_ke,
            'mapel' => $this->record->mapel,
            'materi' => $this->record->materi,
            'catatan' => $this->record->catatan,
            'kehadiran_pembelajaran' => $this->record->kehadiranPembelajaran->map(fn($item) => [
                'siswa_id' => $item->siswa_id,
                'status' => $item->status,
                'keterangan' => $item->keterangan,
            ])->toArray(),
        ]);

        $this->loadKehadiranSekolah();

        // Perintah untuk membuka modal setelah data selesai dimuat
        $this->dispatch('open-modal', id: 'jurnal-modal');
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

        $this->siswaTidakHadir = KehadiranSekolah::query()
            ->whereDate('tanggal', $tanggal)
            ->whereIn('status', ['izin', 'sakit', 'alfa'])
            ->whereHas('siswa.kelasAktif', fn($query) => $query->where('kelas_id', $kelasId))
            ->with('siswa')
            ->orderBy('status')
            ->get();

        $this->daftarSiswa = Siswa::query()
            ->whereHas('kelasAktif', fn($query) => $query->where('kelas_id', $kelasId))
            ->whereHas('kehadiranSekolah', function ($query) use ($tanggal) {
                $query->whereDate('tanggal', $tanggal)
                    ->whereIn('status', ['hadir', 'terlambat']);
            })
            ->orderBy('nama')
            ->get();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->live()
                    ->afterStateUpdated(fn() => $this->loadKehadiranSekolah())
                    ->required(),
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->options(
                        Kelas::query()
                            ->orderBy('tingkat')
                            ->orderBy('nama_kelas')
                            ->pluck('nama_kelas', 'id')
                    )
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn() => $this->loadKehadiranSekolah())
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
                        // Opsi gabungan bawaan awal
                        '1-2' => '1 - 2',
                        '3-4' => '3 - 4',
                        '5-6' => '5 - 6',
                        '7-8' => '7 - 8',
                        // TAMBAHAN: Opsi gabungan baru untuk jam pelajaran silang
                        '2-3' => '2 - 3',
                        '4-5' => '4 - 5',
                        '6-7' => '6 - 7',
                        '8-9' => '8 - 9',
                    ])
                    ->required(),
                TextInput::make('mapel')
                    ->label('Mata Pelajaran')
                    ->required(),
                Textarea::make('materi')
                    ->label('Materi')
                    ->rows(5)
                    ->required(),
                Repeater::make('kehadiran_pembelajaran')
                    ->label('Ketidakhadiran Saat Pembelajaran')
                    ->schema([
                        Select::make('siswa_id')
                            ->label('Siswa')
                            ->options(fn() => $this->daftarSiswa->pluck('nama', 'id')->toArray())
                            ->searchable()
                            ->preload()          // ← tambahkan ini
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'bolos' => 'Bolos',
                                'ke_bk' => 'Ke BK',
                                'ke_uks' => 'Ke UKS',
                                'dispensasi' => 'Dispensasi',
                                'lomba' => 'Lomba',
                                'lainnya' => 'Lainnya',
                            ])
                            ->required(),
                        TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->maxLength(255),
                    ])
                    ->defaultItems(0)
                    ->columns(3)
                    ->collapsible()
                    ->addActionLabel('Tambah Siswa'),
                Textarea::make('catatan')
                    ->label('Catatan')
                    ->rows(3),
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
                    'jam_ke' => $data['jam_ke'],
                    'mapel' => $data['mapel'],
                    'materi' => $data['materi'],
                    'catatan' => $data['catatan'],
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
            ->title($isEdit ? 'Jurnal mengajar berhasil diperbarui.' : 'Jurnal mengajar berhasil disimpan.')
            ->success()
            ->send();

        // Perintah untuk menutup modal setelah berhasil simpan
        $this->dispatch('close-modal', id: 'jurnal-modal');

        $this->record = null;
        $this->form->fill([
            'tanggal' => now('Asia/Jakarta')->toDateString(),
            'kelas_id' => null,
            'jam_ke' => null,
            'mapel' => null,
            'materi' => null,
            'catatan' => null,
            'kehadiran_pembelajaran' => [],
        ]);

        $this->siswaTidakHadir = collect();
        $this->daftarSiswa = collect();
        $this->dispatch('refresh-riwayat-jurnal');
    }

    public function render(): View
    {
        return view('livewire.jurnal-mengajar-form');
    }
}
