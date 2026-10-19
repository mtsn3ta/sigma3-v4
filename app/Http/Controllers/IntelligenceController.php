<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\IzinKeluar;
use App\Models\PermasalahanSiswa;
use App\Models\KonselingSiswa;
use App\Models\KehadiranSekolah;
use App\Models\KehadiranIbadah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IntelligenceController extends Controller
{
    public function index()
    {
        return view('intelligence.index');
    }

    /**
     * Student Intelligence
     * Scan menggunakan RFID
     */
    public function scanSiswa(Request $request)
    {
        try {

            $uid = trim(
                $request->query('uid')
            );

            if ($uid === '') {

                return response()->json([
                    'status' => false,
                    'msg' => 'UID kosong',
                ]);
            }

            $siswa = Siswa::with([

                'kelasAktif.kelas',

                'prestasi' => function ($query) {
                    $query
                        ->orderBy('tanggal', 'desc')
                        ->take(10);
                },

                'anggotaEkskul' => function ($query) {
                    $query->where('status', 'aktif')
                        ->with(['ekstrakurikuler', 'tahunAjaran']); // Memuat ekstrakurikuler dan tahun ajaran
                },

            ])
                ->where('rfid_uid', $uid)
                ->first();

            if (! $siswa) {

                return response()->json([
                    'status' => false,
                    'msg' => 'Data siswa tidak ditemukan',
                ]);
            }

            if ($siswa->status !== 'aktif') {
                return response()->json([
                    'status' => false,
                    'msg' => 'Siswa sudah tidak aktif (Status: ' . ucfirst($siswa->status) . ')',
                ]);
            }

            return response()->json([

                'status' => true,

                'data' => $this->buildStudentIntelligence(
                    $siswa
                ),

            ]);
        } catch (\Throwable $th) {

            return response()->json([

                'status' => false,

                'msg' => 'SYS ERROR : '
                    . $th->getMessage()
                    . ' di baris '
                    . $th->getLine(),

            ], 200);
        }
    }

    /**
     * API Student Intelligence
     * Untuk WhatsApp Bot
     */
    public function api(Request $request)
    {
        try {

            $nisn = trim(
                $request->query('nisn', '')
            );

            if ($nisn === '') {

                return response()->json([
                    'status' => false,
                    'msg' => 'NISN kosong',
                ]);
            }

            $siswa = Siswa::with([

                'kelasAktif.kelas',

                'prestasi' => function ($query) {
                    $query
                        ->orderByDesc('tanggal')
                        ->take(10);
                },

                'anggotaEkskul' => function ($query) {
                    $query->where('status', 'aktif')
                        ->with(['ekstrakurikuler', 'tahunAjaran']); // Memuat ekstrakurikuler dan tahun ajaran
                },

            ])
                ->where('nisn', $nisn)
                ->first();

            if (! $siswa) {

                return response()->json([
                    'status' => false,
                    'msg' => 'Data siswa tidak ditemukan',
                ]);
            }

            if ($siswa->status !== 'aktif') {
                return response()->json([
                    'status' => false,
                    'msg' => 'Siswa sudah tidak aktif (Status: ' . ucfirst($siswa->status) . ')',
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $this->buildStudentIntelligence($siswa),
            ]);
        } catch (\Throwable $th) {

            report($th);

            return response()->json([
                'status' => false,
                'msg' => 'Terjadi kesalahan pada sistem.',
            ], 500);
        }
    }

    /**
     * Build Student Intelligence
     */
    private function buildStudentIntelligence(
        Siswa $siswa
    ): array {
        $idSiswa = $siswa->id;

        $hariIni = now()->format('Y-m-d');

        /*
        |--------------------------------------------------------------------------
        | Absensi Sekolah & Ibadah (PERBAIKAN: Mendukung Jam Datang & Pulang)
        |--------------------------------------------------------------------------
        */

        $kehadiranSekolahRaw = KehadiranSekolah::where('siswa_id', $idSiswa)
            ->whereDate('tanggal', $hariIni)
            ->get();

        $kehadiranSekolah = collect();

        foreach ($kehadiranSekolahRaw as $item) {
            // 1. Baris untuk Absen Datang
            $kehadiranSekolah->push([
                'jenis' => 'Hadir Datang',
                'status' => $item->status_label,
                'jam' => $item->jam_datang ? substr($item->jam_datang, 0, 5) : '-',
            ]);

            // 2. Baris untuk Absen Pulang (Hanya muncul jika jam_pulang sudah terisi / tidak null)
            if ($item->jam_pulang) {
                $kehadiranSekolah->push([
                    'jenis' => 'Hadir Pulang',
                    'status' => $item->status_label,
                    'jam' => substr($item->jam_pulang, 0, 5),
                ]);
            }
        }

        $kehadiranIbadah = KehadiranIbadah::where('siswa_id', $idSiswa)
            ->whereDate('tanggal', $hariIni)
            ->get()
            ->map(function ($item) {
                return [
                    // Menghasilkan nama dinamis seperti "Shalat Dzuhur" atau "Shalat Dhuha"
                    'jenis' => 'Shalat ' . $item->jenis_label,
                    'status' => $item->status_label,
                    // Pastikan format jam ibadah aman baik bertipe string maupun objek Carbon
                    'jam' => $item->waktu_scan 
                        ? (is_string($item->waktu_scan) ? substr($item->waktu_scan, 0, 5) : $item->waktu_scan->format('H:i'))
                        : '-',
                ];
            });

        $absensi = $kehadiranSekolah->concat($kehadiranIbadah);

        /*
        |--------------------------------------------------------------------------
        | Izin Keluar
        |--------------------------------------------------------------------------
        */

        $izin = IzinKeluar::where(
            'siswa_id',
            $idSiswa,
        )
            ->whereDate(
                'tanggal',
                $hariIni,
            )
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | BK
        |--------------------------------------------------------------------------
        */

        $totalKasus = PermasalahanSiswa::where(
            'siswa_id',
            $idSiswa,
        )->count();

        $totalPoin = PermasalahanSiswa::where(
            'siswa_id',
            $idSiswa,
        )->sum('poin');

        $konseling = KonselingSiswa::where(
            'siswa_id',
            $idSiswa,
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Ekstrakurikuler
        |--------------------------------------------------------------------------
        */

        $ekskul = $siswa->anggotaEkskul
            ->filter(function ($anggota) {
                // Mengambil properti tahun ajaran
                $ta = $anggota->tahunAjaran;

                if (! $ta) {
                    return false;
                }

                // Otomatis mencocokkan apa pun nama kolom aktif di database Anda
                return ($ta->is_aktif == true)
                    || ($ta->status === 'aktif')
                    || ($ta->aktif == true)
                    || ($ta->status === 'aktif_sekarang');
            })
            ->map(function ($anggota) {
                return $anggota->ekstrakurikuler->nama
                    ?? $anggota->ekstrakurikuler->nama_ekskul
                    ?? 'Unknown';
            })
            ->unique()   // Menghapus duplikasi nama ekskul (Pramuka Pramuka -> Pramuka)
            ->values()   // Mereset ulang index array agar berurutan dari 0
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Prestasi
        |--------------------------------------------------------------------------
        */

        $prestasi = $siswa->prestasi
            ->map(function ($p) {

                return [

                    'nama_lomba' =>
                    $p->nama_lomba
                        ??
                        $p->nama_prestasi,

                    'tingkat' => $p->tingkat,

                    'tahun' => $p->tanggal
                        ? $p->tanggal->format('Y')
                        : '-',

                ];
            });

        /*
        |--------------------------------------------------------------------------
        | e-Rapor
        |--------------------------------------------------------------------------
        */

        $raporTimeline = [];

        if (

            $siswa->nama_ayah

            &&

            $siswa->nama_ibu

            &&

            $siswa->tanggal_lahir

            &&

            $siswa->jenis_kelamin

        ) {

            try {

                $siswaErapor = DB::connection('erapor')

                    ->table('e_siswa')

                    ->whereRaw(
                        'LOWER(TRIM(nama_ayah)) = ?',
                        [
                            strtolower(
                                trim($siswa->nama_ayah)
                            ),
                        ]
                    )

                    ->whereRaw(
                        'LOWER(TRIM(nama_ibu)) = ?',
                        [
                            strtolower(
                                trim($siswa->nama_ibu)
                            ),
                        ]
                    )

                    ->whereDate(
                        'siswa_tgllahir',
                        $siswa->tanggal_lahir->format('Y-m-d')
                    )

                    ->whereRaw(
                        'UPPER(TRIM(siswa_gender)) = ?',
                        [
                            strtoupper(
                                trim($siswa->jenis_kelamin)
                            ),
                        ]
                    )

                    ->select('siswa_id')

                    ->first();

                if ($siswaErapor) {

                    $eRaporData = DB::connection('erapor')

                        ->table('e_rapor as r')

                        ->join(
                            'e_mapel as m',
                            'm.mapel_id',
                            '=',
                            'r.mapel_id'
                        )

                        ->join(
                            'e_tahunajaran as t',
                            't.tahunajaran_id',
                            '=',
                            'r.tahunajaran_id'
                        )

                        ->join(
                            'e_semester as sm',
                            'sm.semester_id',
                            '=',
                            'r.semester_id'
                        )

                        ->where(
                            'r.siswa_id',
                            $siswaErapor->siswa_id
                        )

                        ->whereIn(
                            'r.semester_id',
                            [1, 2]
                        )

                        ->orderBy('r.tahunajaran_id')

                        ->orderBy('r.semester_id')

                        ->orderBy('m.mapel_nama')

                        ->select(
                            't.tahunajaran_nama',
                            'sm.semester_nama',
                            'm.mapel_nama',
                            'r.rapor_nilai',
                            'r.rapor_predikat',
                        )

                        ->get();

                    $tempTimeline = [];

                    foreach ($eRaporData as $row) {

                        $key = $row->tahunajaran_nama
                            . '|'
                            . $row->semester_nama;

                        if (! isset($tempTimeline[$key])) {

                            $tempTimeline[$key] = [

                                'tahun' => $row->tahunajaran_nama,

                                'semester' => $row->semester_nama,

                                'total_nilai' => 0,

                                'jumlah_mapel' => 0,

                                'detail' => [],

                            ];
                        }

                        $nilai = (float) $row->rapor_nilai;

                        $tempTimeline[$key]['total_nilai'] += $nilai;

                        $tempTimeline[$key]['jumlah_mapel']++;

                        $tempTimeline[$key]['detail'][] = [

                            'mapel' => $row->mapel_nama,

                            'nilai' => $nilai,

                            'predikat' => $row->rapor_predikat,

                        ];
                    }
                    foreach ($tempTimeline as $item) {

                        $avg = $item['jumlah_mapel'] > 0
                            ? round(
                                $item['total_nilai']
                                    / $item['jumlah_mapel'],
                                1
                            )
                            : 0;

                        $raporTimeline[] = [

                            'tahun' => $item['tahun'],

                            'semester' => $item['semester'],

                            'average' => $avg,

                            'detail' => $item['detail'],

                        ];
                    }
                }
            } catch (\Exception $e) {

                Log::error('E-RAPOR OFFLINE: Gagal konek database e-rapor - ' . $e->getMessage());
            
                $raporTimeline = [];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Final Response
        |--------------------------------------------------------------------------
        */

        return [

            'nisn' => $siswa->nisn,

            'nama' => $siswa->nama,

            'jenis_kelamin' => $siswa->jenis_kelamin,

            'alamat' => $siswa->alamat,

            'no_hp' => $siswa->no_hp,

            'rfid_uid' => $siswa->rfid_uid,

            'status' => $siswa->status,

            'tempat_lahir' => $siswa->tempat_lahir,

            'tanggal_lahir' => $siswa->tanggal_lahir
                ? $siswa->tanggal_lahir->format('Y-m-d')
                : null,

            'foto' => ! empty($siswa->foto)
                ? asset('storage/' . $siswa->foto)
                : null,

            'nama_ayah' => $siswa->nama_ayah,

            'nama_ibu' => $siswa->nama_ibu,

            'telpon_ortu' => $siswa->telpon_ortu,

            'nama_wali' => $siswa->nama_wali,

            'sekolah_asal' => $siswa->sekolah_asal,

            'kelas' => $siswa->nama_kelas_aktif ?? '-',

            'absensi' => $absensi,

            'izin_keluar' => $izin
                ? [

                    'tujuan' => $izin->tujuan,

                    'status' => $izin->status_label,

                    'jam_keluar' => $izin->jam_keluar_formatted,

                    'jam_kembali' => $izin->jam_kembali_formatted,

                ]
                : null,

            'bk' => [

                'total_kasus' => (int) $totalKasus,

                'total_poin' => (int) $totalPoin,

            ],

            'konseling' => (int) $konseling,

            'ekskul' => $ekskul,

            'prestasi' => $prestasi,

            'rapor_timeline' => $raporTimeline,

        ];
    }
}
