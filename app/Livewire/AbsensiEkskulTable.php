<?php

namespace App\Livewire;

use App\Models\AnggotaEkskul;
use App\Models\KehadiranEkskul;
use App\Models\SesiKegiatan;
use App\Support\TahunAjaranHelper;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AbsensiEkskulTable extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];
    public array $anggota = [];
    public string $search = '';
    public bool $tandaiSelesai = false;

    public SesiKegiatan $sesi;

    public function mount(SesiKegiatan $sesi): void
    {
        $this->sesi = $sesi;
        $this->tandaiSelesai = ($this->sesi->status === 'selesai');
        $this->loadAnggota();
    }

    protected function loadAnggota(): void
    {
        $anggota = AnggotaEkskul::query()
            ->where('ekstrakurikuler_id', $this->sesi->ekstrakurikuler_id)
            ->where('status', 'aktif')
            ->where('tahun_ajaran_id', TahunAjaranHelper::aktifId())
            ->whereHas('siswa', fn ($q) => $q->where('status', 'aktif'))
            ->with(['siswa.kelasAktif.kelas'])
            ->get()
            ->sortBy('siswa.nama')
            ->values();

        $kehadiran = KehadiranEkskul::query()
            ->where('sesi_kegiatan_id', $this->sesi->id)
            ->get()
            ->keyBy('siswa_id');

        $this->anggota = $anggota
            ->map(function (AnggotaEkskul $anggota) use ($kehadiran) {
                $absen = $kehadiran->get($anggota->siswa_id);

                return [
                    'anggota_id' => $anggota->id,
                    'siswa_id' => $anggota->siswa_id,
                    'nisn' => $anggota->siswa->nisn,
                    'nama' => $anggota->siswa->nama,
                    'kelas' => $anggota->siswa->kelasAktif?->kelas?->nama_kelas ?? '-',
                    'jenis_kelamin' => $anggota->siswa->jenis_kelamin ?? 'L',
                    'status' => $absen?->status ?? 'tidak_hadir',
                    'rfid' => $absen?->sumber === 'rfid',
                    'waktu_scan' => $absen?->waktu_scan ? $absen->waktu_scan->format('H:i') : null,
                ];
            })
            ->toArray();
    }

    public function getFilteredAnggotaProperty(): array
    {
        if (blank($this->search)) {
            return $this->anggota;
        }

        $keyword = strtolower(trim($this->search));

        return array_filter($this->anggota, function ($item) use ($keyword) {
            return str_contains(strtolower($item['nama']), $keyword)
                || str_contains(strtolower($item['kelas'] ?? ''), $keyword)
                || str_contains(strtolower($item['nisn'] ?? ''), $keyword);
        });
    }

    public function markAllHadir(): void
    {
        foreach ($this->anggota as $i => $item) {
            if (! $item['rfid']) {
                $this->anggota[$i]['status'] = 'hadir';
            }
        }

        Notification::make()
            ->info()
            ->title('Semua Ditandai Hadir')
            ->body('Seluruh anggota berhasil diubah ke status Hadir.')
            ->send();
    }

    public function markAllTidakHadir(): void
    {
        foreach ($this->anggota as $i => $item) {
            if (! $item['rfid']) {
                $this->anggota[$i]['status'] = 'tidak_hadir';
            }
        }

        Notification::make()
            ->warning()
            ->title('Semua Ditandai Tidak Hadir')
            ->body('Seluruh anggota berhasil diubah ke status Tidak Hadir.')
            ->send();
    }

    public function setStatus(int $index, string $status): void
    {
        if (isset($this->anggota[$index]) && ! $this->anggota[$index]['rfid']) {
            $this->anggota[$index]['status'] = $status;
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([])
            ->statePath('data');
    }

    public function submit(): void
    {
        $hadirCount = 0;
        $tidakHadirCount = 0;

        foreach ($this->anggota as $anggota) {
            if ($anggota['status'] === 'hadir') {
                $hadirCount++;
            } else {
                $tidakHadirCount++;
            }

            KehadiranEkskul::updateOrCreate(
                [
                    'sesi_kegiatan_id' => $this->sesi->id,
                    'siswa_id'         => $anggota['siswa_id'],
                ],
                [
                    'status'      => $anggota['status'],
                    'sumber'      => $anggota['rfid'] ? 'rfid' : 'manual',
                    'petugas_id'  => Auth::id(),
                    'waktu_scan'  => now(),
                ]
            );
        }

        if ($this->tandaiSelesai) {
            $this->sesi->update(['status' => 'selesai']);
        } elseif ($this->sesi->status === 'draft') {
            $this->sesi->update(['status' => 'aktif']);
        }

        $this->loadAnggota();

        Notification::make()
            ->success()
            ->title('Absensi Berhasil Disimpan')
            ->body("Kehadiran tersimpan: {$hadirCount} Hadir, {$tidakHadirCount} Tidak Hadir." . ($this->tandaiSelesai ? ' Sesi kegiatan telah selesai.' : ''))
            ->send();
    }

    public function render(): View
    {
        return view('livewire.absensi-ekskul-table', [
            'filteredAnggota' => $this->filteredAnggota,
            'totalAnggota' => count($this->anggota),
            'totalHadir' => count(array_filter($this->anggota, fn ($a) => $a['status'] === 'hadir')),
            'totalTidakHadir' => count(array_filter($this->anggota, fn ($a) => $a['status'] === 'tidak_hadir')),
        ]);
    }
}
