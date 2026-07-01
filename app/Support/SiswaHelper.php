<?php

namespace App\Support;

use App\Models\KelasSiswa;
use App\Models\Siswa;
use App\Models\KehadiranSekolah;

class SiswaHelper
{
    /**
     * Daftar siswa aktif berdasarkan kelas.
     */
    public static function byKelas(?int $kelasId): array
    {
        if (! $kelasId) {
            return [];
        }

        return KelasSiswa::query()
            ->where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->with('siswa')
            ->get()
            ->pluck('siswa.nama', 'siswa.id')
            ->toArray();
    }

    /**
     * Collection siswa yang belum melakukan RFID hari ini.
     */
    public static function belumRfid(?int $kelasId = null)
    {
        return Siswa::query()

            ->where('status', 'aktif')

            ->whereHas('kelasAktif', function ($query) use ($kelasId) {

                if ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                }
            })

            ->whereDoesntHave('kehadiranSekolah', function ($query) {

                $query->whereDate('tanggal', today());
            })

            ->orderBy('nama')

            ->get();
    }

    /**
     * Dropdown siswa yang belum RFID.
     */
    public static function belumRfidOptions(?int $kelasId = null): array
    {
        return self::belumRfid($kelasId)

            ->pluck('nama', 'id')

            ->toArray();
    }
    public static function belumVerifikasiIbadahOptions(
        ?int $kelasId,
        ?string $tanggal,
        ?string $jenis,
    ): array {

        if (
            blank($kelasId) ||
            blank($tanggal) ||
            blank($jenis)
        ) {
            return [];
        }

        return Siswa::query()

            ->where('status', 'aktif')

            ->whereHas(

                'kelasAktif',

                fn($query) =>

                $query->where(
                    'kelas_id',
                    $kelasId
                )

            )

            ->whereHas(

                'kehadiranSekolah',

                fn($query) =>

                $query

                    ->whereDate(
                        'tanggal',
                        $tanggal
                    )

                    ->whereIn(
                        'status',
                        [
                            'hadir',
                            'terlambat',
                        ]
                    )

            )

            ->whereDoesntHave(

                'kehadiranIbadah',

                fn($query) =>

                $query

                    ->whereDate(
                        'tanggal',
                        $tanggal
                    )

                    ->where(
                        'jenis',
                        $jenis
                    )

            )

            ->whereDoesntHave(

                'izinKeluar',

                fn($query) =>

                $query

                    ->whereDate(
                        'tanggal',
                        $tanggal
                    )

                    ->where(
                        'status_proses',
                        'keluar'
                    )

            )

            ->orderBy('nama')

            ->pluck(
                'nama',
                'id'
            )

            ->toArray();
    }
    /**
     * Jumlah siswa aktif.
     */
    public static function count(): int
    {
        return Siswa::query()

            ->where('status', 'aktif')

            ->count();
    }
    public static function attendanceSummary(): array
    {
        $query = KehadiranSekolah::query()
            ->whereDate('tanggal', today());

        $hadir = (clone $query)->where('status', 'hadir')->count();

        $terlambat = (clone $query)->where('status', 'terlambat')->count();

        $sakit = (clone $query)->where('status', 'sakit')->count();

        $izin = (clone $query)->where('status', 'izin')->count();

        $alfa = (clone $query)->where('status', 'alfa')->count();

        $total = self::count();

        $persentase = $total > 0
            ? round((($hadir + $terlambat) / $total) * 100)
            : 0;

        return [
            'today' => today()->translatedFormat('l, d F Y'),

            'hadir' => $hadir,

            'terlambat' => $terlambat,

            'sakit' => $sakit,

            'izin' => $izin,

            'alfa' => $alfa,

            'persentase' => $persentase,
        ];
    }
}
