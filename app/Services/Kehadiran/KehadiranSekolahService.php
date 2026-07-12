<?php

namespace App\Services\Kehadiran;

use App\Models\KehadiranSekolah;
use App\Models\Pegawai;
use App\Models\Siswa;
use App\Support\SiswaHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use RuntimeException;

class KehadiranSekolahService
{
    /*
    |--------------------------------------------------------------------------
    | Query
    |--------------------------------------------------------------------------
    */

    public function findToday(Siswa $siswa, ?string $tanggal = null): ?KehadiranSekolah
    {
        // PERBAIKAN: Jika tanggal tidak dikirim, gunakan hari ini berbasis Asia/Jakarta
        $targetTanggal = $tanggal ?? now('Asia/Jakarta')->toDateString();

        return KehadiranSekolah::query()
            ->where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $targetTanggal)
            ->first();
    }

    public function today(): Builder
    {
        // PERBAIKAN: Kunci query ringkasan data harian ke zona waktu Asia/Jakarta
        return KehadiranSekolah::query()
            ->whereDate('tanggal', now('Asia/Jakarta')->toDateString());
    }

    public function belumRfid(?int $kelasId = null): Collection
    {
        return SiswaHelper::belumRfid($kelasId);
    }

    /*
    |--------------------------------------------------------------------------
    | RFID
    |--------------------------------------------------------------------------
    */

    public function markRfidDatang(Siswa $siswa, Carbon $waktu): KehadiranSekolah
    {
        return KehadiranSekolah::firstOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal'  => $waktu->toDateString(),
            ],
            [
                'status' => 'hadir',
                'sumber' => 'rfid',
                'jam_datang' => $waktu->format('H:i:s'),
            ]
        );
    }

    public function markRfidPulang(Siswa $siswa, Carbon $waktu): KehadiranSekolah
    {
        // Tetap mencari data berdasarkan tanggal RFID ditempel
        $record = $this->findToday($siswa, $waktu->toDateString());

        if (! $record) {
            throw new RuntimeException(
                'Data kehadiran datang belum ada.'
            );
        }

        $record->update([
            'jam_pulang' => $waktu->format('H:i:s'),
        ]);

        return $record->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Guru Piket (Dukungan Parameter Tanggal Ditambahkan)
    |--------------------------------------------------------------------------
    */

    public function markManualHadir(Siswa $siswa, string $jamDatang, Pegawai $petugas, ?string $tanggal = null): KehadiranSekolah
    {
        return KehadiranSekolah::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal ?? now('Asia/Jakarta')->toDateString(),
            ],
            [
                'status' => 'hadir',
                'sumber' => 'manual',
                'jam_datang' => $jamDatang,
                'petugas_id' => $petugas->id,
            ]
        );
    }

    public function markManualTerlambat(Siswa $siswa, string $jamDatang, Pegawai $petugas, ?string $tanggal = null): KehadiranSekolah
    {
        return KehadiranSekolah::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal ?? now('Asia/Jakarta')->toDateString(),
            ],
            [
                'status' => 'terlambat',
                'sumber' => 'manual',
                'jam_datang' => $jamDatang,
                'petugas_id' => $petugas->id,
            ]
        );
    }

    public function markSakit(Siswa $siswa, ?string $keterangan, Pegawai $petugas, ?string $tanggal = null): KehadiranSekolah
    {
        return KehadiranSekolah::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal ?? now('Asia/Jakarta')->toDateString(),
            ],
            [
                'status' => 'sakit',
                'sumber' => 'manual',
                'keterangan' => $keterangan,
                'petugas_id' => $petugas->id,
            ]
        );
    }

    public function markIzin(Siswa $siswa, ?string $keterangan, Pegawai $petugas, ?string $tanggal = null): KehadiranSekolah
    {
        return KehadiranSekolah::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal ?? now('Asia/Jakarta')->toDateString(),
            ],
            [
                'status' => 'izin',
                'sumber' => 'manual',
                'keterangan' => $keterangan,
                'petugas_id' => $petugas->id,
            ]
        );
    }

    public function markAlfa(Siswa $siswa, ?string $keterangan, Pegawai $petugas, ?string $tanggal = null): KehadiranSekolah
    {
        return KehadiranSekolah::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $tanggal ?? now('Asia/Jakarta')->toDateString(),
            ],
            [
                'status' => 'alfa',
                'sumber' => 'manual',
                'keterangan' => $keterangan,
                'petugas_id' => $petugas->id,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Monitoring Query
    |--------------------------------------------------------------------------
    */

    public function hadir(): Builder
    {
        return $this->today()->where('status', 'hadir');
    }
    public function terlambat(): Builder
    {
        return $this->today()->where('status', 'terlambat');
    }
    public function sakit(): Builder
    {
        return $this->today()->where('status', 'sakit');
    }
    public function izin(): Builder
    {
        return $this->today()->where('status', 'izin');
    }
    public function alfa(): Builder
    {
        return $this->today()->where('status', 'alfa');
    }
    public function sudahHadir(): Builder
    {
        return $this->today()->where('status', ['hadir', 'terlambat']);
    }
    public function belumPulang(): Builder
    {
        return $this->today()->whereNotNull('jam_datang')->whereNull('jam_pulang');
    }
    public function sudahPulang(): Builder
    {
        return $this->today()->whereNotNull('jam_pulang');
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Counter
    |--------------------------------------------------------------------------
    */

    public function jumlahHadir(): int
    {
        return $this->hadir()->count();
    }
    public function jumlahTerlambat(): int
    {
        return $this->terlambat()->count();
    }
    public function jumlahSakit(): int
    {
        return $this->sakit()->count();
    }
    public function jumlahIzin(): int
    {
        return $this->izin()->count();
    }
    public function jumlahAlfa(): int
    {
        return $this->alfa()->count();
    }
    public function jumlahSudahHadir(): int
    {
        return $this->sudahHadir()->count();
    }
    public function jumlahBelumRfid(?int $kelasId = null): int
    {
        return $this->belumRfid($kelasId)->count();
    }
    public function jumlahBelumPulang(): int
    {
        return $this->belumPulang()->count();
    }
    public function jumlahSudahPulang(): int
    {
        return $this->sudahPulang()->count();
    }
}
