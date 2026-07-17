<?php

namespace App\Services\RFID;

use App\Models\Siswa;
use App\Services\Kehadiran\KehadiranSekolahService;
use App\Support\SettingHelper;
use App\Services\RFID\RfidFailedLogger;
use RuntimeException;

class SchoolRfidService
{
    protected ?string $uid = null;
    public function __construct(
        protected KehadiranSekolahService $attendance,
        protected RfidFailedLogger $logger,
    ) {}

    public function scan(string $uid): array
    {
        $this->uid = $uid;
        $this->validateSetting();

        $student = $this->findStudent($uid);

        if (! $student) {
            return $this->error('unknown_card');
        }

        $action = $this->resolveAction();

        if (! $action) {
            // PERBAIKAN: Masukkan variabel $student di sini agar Nama dan Kelas ikut tercatat di Log
            return $this->error('outside_time', $student);
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
        // PERBAIKAN: Paksa Carbon menggunakan zona waktu Asia/Jakarta (WIB)
        $nowTime = now('Asia/Jakarta');

        // 1. Ambil total detik dari jam server saat ini (00:00:00) berbasis WIB
        $nowSeconds = ($nowTime->hour * 3600) + ($nowTime->minute * 60) + $nowTime->second;

        // 2. Batas Waktu Masuk (Check-in)
        $startStr = SettingHelper::rfidSchoolCheckinStart(); // '23:00:00'
        $endStr = SettingHelper::rfidSchoolCheckinEnd();     // '06:59:00'

        $startParts = explode(':', $startStr);
        $endParts = explode(':', $endStr);

        // Koreksi Indeks Array [0] = Jam, [1] = Menit, [2] = Detik
        $checkinStartSec = ((int)$startParts[0] * 3600) + ((int)$startParts[1] * 60) + (isset($startParts[2]) ? (int)$startParts[2] : 0);
        $checkinEndSec = ((int)$endParts[0] * 3600) + ((int)$endParts[1] * 60) + (isset($endParts[2]) ? (int)$endParts[2] : 0);

        // Evaluasi apakah masuk jam Check-in (Mendukung lewat tengah malam)
        $isCheckin = $checkinStartSec <= $checkinEndSec
            ? ($nowSeconds >= $checkinStartSec && $nowSeconds <= $checkinEndSec)
            : ($nowSeconds >= $checkinStartSec || $nowSeconds <= $checkinEndSec);

        if ($isCheckin) {
            return 'checkin';
        }

        // 3. Batas Waktu Pulang (Check-out)
        $outStartStr = SettingHelper::rfidSchoolCheckoutStart();
        $outEndStr = SettingHelper::rfidSchoolCheckoutEnd();

        $outStartParts = explode(':', $outStartStr);
        $outEndParts = explode(':', $outEndStr);

        // Koreksi Indeks Array [0] = Jam, [1] = Menit, [2] = Detik
        $checkoutStartSec = ((int)$outStartParts[0] * 3600) + ((int)$outStartParts[1] * 60) + (isset($outStartParts[2]) ? (int)$outStartParts[2] : 0);
        $checkoutEndSec = ((int)$outEndParts[0] * 3600) + ((int)$outEndParts[1] * 60) + (isset($outEndParts[2]) ? (int)$outEndParts[2] : 0);

        // Evaluasi apakah masuk jam Check-out (Mendukung lewat tengah malam)
        $isCheckout = $checkoutStartSec <= $checkoutEndSec
            ? ($nowSeconds >= $checkoutStartSec && $nowSeconds <= $checkoutEndSec)
            : ($nowSeconds >= $checkoutStartSec || $nowSeconds <= $checkoutEndSec);

        if ($isCheckout) {
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

            // PERBAIKAN: Paksa simpan jam datang menggunakan Asia/Jakarta
            $this->attendance->markRfidDatang(
                $student,
                now('Asia/Jakarta'),
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

        // PERBAIKAN: Tangkap hasil return untuk mencegah Error 500
        $updatedRecord = $this->attendance->markRfidPulang(
            $student,
            now('Asia/Jakarta'),
        );

        // Jika data tiba-tiba tidak ditemukan karena perbedaan zona waktu, kembalikan pesan error biasa
        if (! $updatedRecord) {
            return $this->error(
                'checkin_required',
                $student,
            );
        }

        return $this->success(
            $student,
            $action,
        );
    }

    protected function success(
        Siswa $student,
        string $action,
    ): array {

        // PERBAIKAN: Buat objek penanda waktu berbasis Asia/Jakarta untuk data JSON response
        $nowTime = now('Asia/Jakarta');

        return [

            'status' => true,

            'mode' => 'school',

            'action' => $action,

            'message' => $action === 'checkin'
                ? 'Absen Berhasil.'
                : 'Hati-hati di jalan.',

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

        $this->logger->store(
            mode: 'school',
            uid: $this->uid,
            error: $type,
            message: $this->errorMessage($type),
            student: $student,
        );

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
