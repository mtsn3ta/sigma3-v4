<?php

namespace App\Services\RFID;

use App\Models\Siswa;
use App\Services\Kehadiran\KehadiranIbadahService;
use App\Support\SettingHelper;
use App\Services\RFID\RfidFailedLogger;
use RuntimeException;

class IbadahRfidService
{
    protected ?string $uid = null;
    public function __construct(
        protected KehadiranIbadahService $attendance,
        protected RfidFailedLogger $logger,
    ) {}

    public function scan(string $uid): array
    {
        // Pindahkan deklarasi awal agar $student bisa diakses di blok catch
        $student = null;

        try {
            $this->uid = $uid;
            $this->validateSetting();

            $student = $this->findStudent($uid);

            if (! $student) {
                return $this->error('unknown_card');
            }

            $jenis = $this->resolveJenis();

            $this->validateFriday($jenis);

            return $this->process(
                $student,
                $jenis,
            );
        } catch (RuntimeException $e) {
            // PERBAIKAN: Sertakan variabel $student sebagai parameter kedua di sini
            return $this->error(
                $e->getMessage(),
                $student
            );
        }
    }


    protected function validateSetting(): void
    {
        if (! SettingHelper::rfidIbadahEnabled()) {
            throw new RuntimeException(
                'disabled'
            );
        }
    }

    protected function validateFriday(
        string $jenis,
    ): void {
        if ($jenis !== 'dzuhur') {
            return;
        }

        // PERBAIKAN: Paksa pengecekan hari Jumat menggunakan zona waktu Asia/Jakarta
        if (
            now('Asia/Jakarta')->isFriday()
            && SettingHelper::rfidDisableFriday()
        ) {
            throw new RuntimeException(
                'disabled_today'
            );
        }
    }

    protected function findStudent(string $uid): ?Siswa
    {
        return Siswa::query()
            ->aktif()
            ->where('rfid_uid', $uid)
            ->first();
    }

    protected function resolveJenis(): string
    {
        // PERBAIKAN: Ambil jam saat ini berbasis waktu Asia/Jakarta (WIB)
        $now = now('Asia/Jakarta')->format('H:i:s');

        if (
            $now >= SettingHelper::rfidDhuhaStart()
            && $now <= SettingHelper::rfidDhuhaEnd()
        ) {
            return 'dhuha';
        }

        if (
            $now >= SettingHelper::rfidDzuhurStart()
            && $now <= SettingHelper::rfidDzuhurEnd()
        ) {
            return 'dzuhur';
        }

        throw new RuntimeException(
            'outside_time'
        );
    }

    protected function process(
        Siswa $student,
        string $jenis,
    ): array {
        $today = $this->attendance
            ->findToday(
                $student,
                $jenis,
            );

        if ($today) {
            return $this->error(
                'already_scanned',
                $student,
            );
        }

        $this->attendance
            ->markRfidHadir(
                $student,
                $jenis,
            );

        return $this->success(
            $student,
            $jenis,
        );
    }

    protected function success(
        Siswa $student,
        string $jenis,
    ): array {
        // PERBAIKAN: Gunakan waktu Asia/Jakarta untuk respon ke client
        $nowTime = now('Asia/Jakarta');

        return [
            'status' => true,
            'mode' => 'ibadah',
            'jenis' => $jenis,
            'action' => $jenis,
            'message' => $jenis === 'dhuha'
                ? 'Selamat Menunaikan Sholat Dhuha.'
                : 'Selamat Menunaikan Sholat Dzuhur.',
            'server_time' => $nowTime->format('H:i:s'),
            'server_date' => $nowTime->toDateString(),
            'student' => [
                'id' => $student->id,
                'uid' => $student->rfid_uid,
                'nama' => $student->nama,
                'kelas' => $student->nama_kelas_aktif,
                'photo' => $student->foto
                    ? asset('storage/' . $student->foto)
                    : null,
            ],
        ];
    }

    protected function error(
        string $type,
        ?Siswa $student = null,
    ): array {
        // PERBAIKAN: Gunakan waktu Asia/Jakarta untuk pencatatan log error
        $nowTime = now('Asia/Jakarta');

        $this->logger->store(
            mode: 'ibadah',
            uid: $this->uid,
            error: $type,
            message: $this->errorMessage($type),
            student: $student,
        );

        return [
            'status' => false,
            'mode' => 'ibadah',
            'error' => $type,
            'message' => $this->errorMessage($type),
            'server_time' => $nowTime->format('H:i:s'),
            'server_date' => $nowTime->toDateString(),
            'student' => $student ? [
                'id' => $student->id,
                'uid' => $student->rfid_uid,
                'nama' => $student->nama,
                'kelas' => $student->nama_kelas_aktif,
                'photo' => $student->foto
                    ? asset('storage/' . $student->foto)
                    : null,
            ] : null,
        ];
    }

    protected function errorMessage(
        string $type,
    ): string {
        return match ($type) {
            'disabled' => 'RFID Ibadah sedang dinonaktifkan.',
            'disabled_today' => 'RFID Ibadah tidak digunakan hari ini.',
            'unknown_card' => 'Kartu RFID tidak terdaftar.',
            'already_scanned' => 'Ibadah sudah tercatat.',
            'outside_time' => 'Di luar jam ibadah.',
            default => 'Terjadi kesalahan.',
        };
    }
}
