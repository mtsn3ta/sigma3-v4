<?php

namespace App\Services\Monitoring;

use App\Models\IzinKeluar;
use App\Models\KehadiranIbadah;
use App\Models\KehadiranPembelajaran;
use App\Models\KehadiranSekolah;
use App\Models\Siswa;
use App\Support\AttendanceStatusHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MonitoringAttendanceService
{
    public function paginate(
        Carbon $tanggal,
        ?int $kelasId = null,
        ?string $search = null,
    ): LengthAwarePaginator {
        $query = Siswa::query()

            ->with([

                'kelasAktif.kelas',

            ]);
        if ($kelasId) {

            $query->whereHas(

                'kelasAktif',

                fn($q) =>

                $q->where('kelas_id', $kelasId)

            );
        }
        if ($search) {

            $query->where(function ($q) use ($search) {

                $q

                    ->where('nama', 'like', "%{$search}%")

                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        $students =

            $query

            ->orderBy('nama')

            ->paginate(20)

            ->withQueryString();
        $schoolAttendances =

            KehadiranSekolah::query()

            ->whereDate('tanggal', $tanggal)

            ->get()

            ->keyBy('siswa_id');
        $dhuhaAttendances =

            KehadiranIbadah::query()

            ->whereDate('tanggal', $tanggal)

            ->where('jenis', 'dhuha')

            ->get()

            ->keyBy('siswa_id');
        $dzuhurAttendances =

            KehadiranIbadah::query()

            ->whereDate('tanggal', $tanggal)

            ->where('jenis', 'dzuhur')

            ->get()

            ->keyBy('siswa_id');
        $lessonAttendances = KehadiranPembelajaran::query()

            ->with('jurnalMengajar')

            ->whereHas(
                'jurnalMengajar',
                fn($q) => $q->whereDate('tanggal', $tanggal)
            )

            ->get()

            ->groupBy('siswa_id');
        $exitPermissions =

            IzinKeluar::query()

            ->whereDate('tanggal', $tanggal)

            ->get()

            ->keyBy('siswa_id');
        $students = $students->through(function ($student) use (

            $schoolAttendances,

            $dhuhaAttendances,

            $dzuhurAttendances,

            $lessonAttendances,

            $exitPermissions,

        ) {

            $school =

                $schoolAttendances->get($student->id);

            $dhuha =

                $dhuhaAttendances->get($student->id);

            $dzuhur =

                $dzuhurAttendances->get($student->id);

            $lessons =

                $lessonAttendances->get($student->id, collect());

            $exit =

                $exitPermissions->get($student->id);

            return [

                'id' => $student->id,

                'nisn' => $student->nisn,

                'nama' => $student->nama,

                'kelas' => $student->kelasAktif?->kelas?->nama,

                'jam_datang' => $school?->jam_datang,

                'jam_pulang' => $school?->jam_pulang,

                'jam_dhuha' => $dhuha?->waktu_scan,

                'jam_dzuhur' => $dzuhur?->waktu_scan,

                'status' => AttendanceStatusHelper::make(

                    sekolah: $school,

                    dhuha: $dhuha,

                    dzuhur: $dzuhur,

                    pembelajaran: $lessons,

                    izinKeluar: $exit,

                ),
                'photo' => $student->foto
                    ? asset('storage/' . $student->foto)
                    : null,

                'rfid' => [

                    'label' => $school?->sumber_label ?? '-',

                    'code' => $school?->sumber,

                    'is_rfid' => $school?->isRfid() ?? false,

                ],

            ];
        });
        return $students;
    }
    public function statistics(
        Carbon $tanggal,
        ?int $kelasId = null,
        ?string $search = null,
    ): array {

        $rows = collect(
            $this->paginate(
                tanggal: $tanggal,
                kelasId: $kelasId,
                search: $search,
            )->items()
        );

        return [

            'hadir' => $rows->where('status.code', 'hadir')->count(),

            'terlambat' => $rows->where('status.code', 'terlambat')->count(),

            'izin' => $rows->where('status.code', 'izin')->count(),

            'sakit' => $rows->where('status.code', 'sakit')->count(),

            'tidak_hadir' => $rows->where('status.code', 'tidak_hadir')->count(),

            'belum_dhuha' => $rows->where('status.code', 'belum_dhuha')->count(),

            'belum_dzuhur' => $rows->where('status.code', 'belum_dzuhur')->count(),

            'belum_pulang' => $rows->where('status.code', 'belum_pulang')->count(),

            'rfid' => $rows->where('rfid.code', 'rfid')->count(),

            'manual' => $rows->where('rfid.code', 'manual')->count(),

        ];
    }
}
