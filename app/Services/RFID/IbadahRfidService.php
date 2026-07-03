<?php

namespace App\Services\RFID;

use App\Models\Siswa;
use App\Services\Kehadiran\KehadiranIbadahService;
use App\Support\SettingHelper;
use RuntimeException;

class IbadahRfidService
{
    public function __construct(
        protected KehadiranIbadahService $attendance,
    ) {}

    public function scan(string $uid): array
    {
        try {

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

            return $this->error(
                $e->getMessage()
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

        if (

            $jenis !== 'dzuhur'

        ) {

            return;
        }

        if (

            now()->isFriday()

            &&

            SettingHelper::rfidDisableFriday()

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
        $now = now()->format('H:i:s');

        if (

            $now >= SettingHelper::rfidDhuhaStart()

            &&

            $now <= SettingHelper::rfidDhuhaEnd()

        ) {

            return 'dhuha';
        }

        if (

            $now >= SettingHelper::rfidDzuhurStart()

            &&

            $now <= SettingHelper::rfidDzuhurEnd()

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

        return [

            'status' => true,

            'mode' => 'ibadah',

            'jenis' => $jenis,

            'action' => $jenis,


            'message' => $jenis === 'dhuha'
                ? 'Selamat Menunaikan Sholat Dhuha.'
                : 'Selamat Menunaikan Sholat Dzuhur.',

            'server_time' => now()->format('H:i:s'),

            'server_date' => now()->toDateString(),

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

        return [

            'status' => false,

            'mode' => 'ibadah',

            'error' => $type,

            'message' => $this->errorMessage($type),

            'server_time' => now()->format('H:i:s'),

            'server_date' => now()->toDateString(),

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

            'disabled'
            => 'RFID Ibadah sedang dinonaktifkan.',

            'disabled_today'
            => 'RFID Ibadah tidak digunakan hari ini.',

            'unknown_card'
            => 'Kartu RFID tidak terdaftar.',

            'already_scanned'
            => 'Ibadah sudah tercatat.',

            'outside_time'
            => 'Di luar jam ibadah.',

            default
            => 'Terjadi kesalahan.',
        };
    }
}
