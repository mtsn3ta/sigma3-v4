<?php

namespace App\Services\RFID;

use App\Models\Siswa;
use App\Services\Kehadiran\KehadiranSekolahService;
use App\Support\SettingHelper;
use RuntimeException;

class SchoolRfidService
{
    public function __construct(
        protected KehadiranSekolahService $attendance,
    ) {}

    public function scan(string $uid): array
    {
        $this->validateSetting();

        $student = $this->findStudent($uid);

        if (! $student) {
            return $this->error('unknown_card');
        }

        $action = $this->resolveAction();

        if (! $action) {
            return $this->error('outside_time');
        }

        return $this->process(
            $student,
            $action,
        );
    }

    protected function validateSetting(): void
    {
        if (! SettingHelper::rfidSchoolEnabled()) {

            throw new RuntimeException(
                'RFID Sekolah sedang dinonaktifkan.'
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

    protected function resolveAction(): ?string
    {
        $now = now()->format('H:i:s');

        if (
            $now >= SettingHelper::rfidSchoolCheckinStart()
            && $now <= SettingHelper::rfidSchoolCheckinEnd()
        ) {
            return 'checkin';
        }

        if (
            $now >= SettingHelper::rfidSchoolCheckoutStart()
            && $now <= SettingHelper::rfidSchoolCheckoutEnd()
        ) {
            return 'checkout';
        }

        return null;
    }

    protected function process(
        Siswa $student,
        string $action,
    ): array {

        if ($action === 'checkin') {

            $today = $this->attendance
                ->findToday($student);

            if ($today?->jam_datang) {

                return $this->error(
                    'already_scanned',
                    $student,
                );
            }

            $this->attendance->markRfidDatang(
                $student,
                now(),
            );

            return $this->success(
                $student,
                $action,
            );
        }

        $today = $this->attendance
            ->findToday($student);

        if (! $today) {

            return $this->error(
                'checkin_required',
                $student,
            );
        }

        if ($today->jam_pulang) {

            return $this->error(
                'already_scanned',
                $student,
            );
        }

        $this->attendance->markRfidPulang(
            $student,
            now(),
        );

        return $this->success(
            $student,
            $action,
        );
    }

    protected function success(
        Siswa $student,
        string $action,
    ): array {

        return [

            'status' => true,

            'mode' => 'school',

            'action' => $action,

            'message' => $action === 'checkin'
                ? 'Absen Berhasil.'
                : 'Hati-hati di jalan.',

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

            'mode' => 'school',

            'error' => $type,

            'message' => $this->errorMessage($type),

            'server_time' => now()->format('H:i:s'),

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

            'unknown_card' =>
            'Kartu tidak terdaftar.',

            'already_scanned' =>
            'Siswa sudah absen.',

            'outside_time' =>
            'Absensi di luar jam operasional.',

            'checkin_required' =>
            'Silakan lakukan absensi datang terlebih dahulu.',

            default =>
            'Terjadi kesalahan sistem.',
        };
    }
}
