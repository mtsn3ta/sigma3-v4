<?php

namespace App\Services\RFID;

use App\Models\AnggotaEkskul;
use App\Models\SesiKegiatan;
use App\Models\Siswa;
use App\Services\Kehadiran\KehadiranEkskulService;
use App\Services\RFID\RfidFailedLogger;
use RuntimeException;

class EkstrakurikulerRfidService
{
    protected ?string $uid = null;
    public function __construct(
        protected KehadiranEkskulService $attendance,
        protected RfidFailedLogger $logger,
    ) {}

    public function scan(string $uid): array
    {
        $this->uid = $uid;
        $student = null; // Inisialisasi awal agar aman dibaca di dalam blok catch

        try {
            $student = $this->findStudent($uid);

            if (! $student) {
                return $this->error('unknown_card');
            }

            $member = $this->findMember($student);

            if (! $member) {
                return $this->error(
                    'not_member',
                    $student,
                );
            }

            $session = $this->findTodaySession($member);

            if (! $session) {
                return $this->error(
                    'no_session',
                    $student,
                );
            }

            return $this->process(
                $student,
                $session,
            );
        } catch (RuntimeException $e) {
            // PERBAIKAN: Masukkan variabel $student sebagai parameter kedua di sini
            return $this->error(
                $e->getMessage(),
                $student
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

    protected function findMember(Siswa $student): ?AnggotaEkskul
    {
        return AnggotaEkskul::query()
            ->where('siswa_id', $student->id)
            ->where('status', 'aktif')
            ->whereHas('tahunAjaran', function ($query) {
                // PERBAIKAN: Kembalikan murni hanya mengecek kolom 'aktif' karena 'is_aktif' tidak ada di tabel Anda
                $query->where('aktif', true);
            })
            ->first();
    }

    protected function findTodaySession(AnggotaEkskul $member): ?SesiKegiatan
    {
        return SesiKegiatan::query()
            ->with([
                'ekstrakurikuler',
                'pelatih',
            ])
            ->where('ekstrakurikuler_id', $member->ekstrakurikuler_id)
            // PERBAIKAN: Mengunci pencarian tanggal sesi hari ini ke zona waktu Asia/Jakarta (WIB)
            ->whereDate('tanggal', today('Asia/Jakarta'))
            ->first();
    }

    protected function process(Siswa $student, SesiKegiatan $session): array
    {
        $attendance = $this->attendance->find(
            $session,
            $student,
        );

        if (! $attendance) {
            return $this->error(
                'attendance_not_generated',
                $student,
            );
        }

        if ($attendance->isHadir()) {
            return $this->error(
                'already_scanned',
                $student,
            );
        }

        $attendance = $this->attendance
            ->markRfidHadir($attendance);

        return $this->success(
            $student,
            $session,
        );
    }

    protected function success(Siswa $student, SesiKegiatan $session): array
    {
        // PERBAIKAN: Mengunci response jam dan tanggal sukses ke zona waktu Asia/Jakarta (WIB)
        $nowTime = now('Asia/Jakarta');

        return [
            'status' => true,
            'mode' => 'ekskul',
            'message' => 'Selamat Berlatih.',
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
            'activity' => [
                'id' => $session->id,
                'nama' => $session->ekstrakurikuler->nama,
                'pelatih' => $session->pelatih?->nama,
                'mulai' => $session->waktu_mulai,
                'selesai' => $session->waktu_selesai,
            ],
        ];
    }

    protected function error(string $type, ?Siswa $student = null): array
    {
        // PERBAIKAN: Mengunci response jam dan tanggal error log ke zona waktu Asia/Jakarta (WIB)
        $nowTime = now('Asia/Jakarta');

        $this->logger->store(
            mode: 'ekskul',
            uid: $this->uid,
            error: $type,
            message: $this->errorMessage($type),
            student: $student,
        );

        return [
            'status' => false,
            'mode' => 'ekskul',
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

    protected function errorMessage(string $type): string
    {
        return match ($type) {
            'unknown_card' => 'Kartu RFID tidak terdaftar.',
            'not_member' => 'Siswa bukan anggota ekstrakurikuler.',
            'no_session' => 'Belum ada sesi kegiatan hari ini.',
            'already_scanned' => 'Kehadiran sudah tercatat.',
            'attendance_not_generated' => 'Data kehadiran belum tersedia.',
            default => 'Terjadi kesalahan.',
        };
    }
}
