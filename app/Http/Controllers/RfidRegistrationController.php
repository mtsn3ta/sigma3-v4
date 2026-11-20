<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pegawai;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RfidRegistrationController extends Controller
{
    private const SESSION_UNLOCKED = 'rfid_reg_unlocked';
    private const SESSION_PETUGAS = 'rfid_reg_petugas';
    private const SESSION_EXPIRES_AT = 'rfid_reg_expires_at';

    /**
     * Halaman public registrasi RFID siswa (/registrasi-rfid).
     */
    public function show(Request $request): Response
    {
        $unlocked = $this->isUnlocked($request);

        $selectedKelasId = null;
        if ($unlocked) {
            $selectedKelasId = $this->resolveSelectedKelasId($request);
        }

        return Inertia::render('RFID/Registrasi', [
            'unlocked' => $unlocked,
            'petugasName' => $unlocked ? $request->session()->get(self::SESSION_PETUGAS, '') : '',
            'kelasList' => $unlocked ? $this->kelasOptions() : [],
            'selectedKelasId' => $selectedKelasId,
            'requestedKelas' => $request->query('kelas') ?? $request->query('kelas_id'),
        ]);
    }

    /**
     * Verifikasi PIN Petugas dan buka sesi registrasi.
     */
    public function unlock(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pin' => ['required', 'string', 'max:32'],
            'petugas' => ['nullable', 'string', 'max:80'],
        ]);

        $inputPin = trim($data['pin']);
        $expectedPin = $this->expectedPin();

        if (! hash_equals($expectedPin, $inputPin)) {
            return response()->json([
                'ok' => false,
                'message' => 'PIN Petugas salah. Silakan coba lagi.',
            ], 422);
        }

        $petugas = trim((string) ($data['petugas'] ?? ''));
        $petugas = $petugas !== '' ? $petugas : 'Petugas Piket';

        $request->session()->put(self::SESSION_UNLOCKED, true);
        $request->session()->put(self::SESSION_PETUGAS, $petugas);
        $request->session()->put(self::SESSION_EXPIRES_AT, now()->addHours(8)->timestamp);

        return response()->json([
            'ok' => true,
            'petugasName' => $petugas,
            'kelasList' => $this->kelasOptions(),
            'selectedKelasId' => $this->resolveSelectedKelasId($request),
        ]);
    }

    /**
     * Kunci kembali sesi registrasi.
     */
    public function lock(Request $request): JsonResponse
    {
        $request->session()->forget([
            self::SESSION_UNLOCKED,
            self::SESSION_PETUGAS,
            self::SESSION_EXPIRES_AT,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Ambil daftar siswa aktif (berdasarkan kelas atau pencarian).
     */
    public function students(Request $request): JsonResponse
    {
        if (! $this->isUnlocked($request)) {
            return response()->json(['ok' => false, 'message' => 'Sesi terkunci.'], 401);
        }

        $kelasId = $request->integer('kelas_id') ?: null;
        $search = trim((string) $request->query('search', ''));

        if (! $kelasId && mb_strlen($search) < 2) {
            return response()->json([
                'ok' => true,
                'students' => [],
            ]);
        }

        $query = Siswa::query()
            ->where('status', 'aktif')
            ->with(['kelasAktif.kelas:id,nama_kelas'])
            ->orderBy('nama');

        if ($kelasId) {
            $query->whereHas('kelasAktif', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query->limit(200)->get()->map(fn (Siswa $siswa) => $this->formatStudent($siswa));

        return response()->json([
            'ok' => true,
            'students' => $students,
        ]);
    }

    /**
     * Simpan RFID UID ke siswa (Mode Aman: hanya isi jika masih kosong).
     */
    public function assign(Request $request): JsonResponse
    {
        if (! $this->isUnlocked($request)) {
            return response()->json(['ok' => false, 'message' => 'Sesi terkunci.'], 401);
        }

        $data = $request->validate([
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'rfid_uid' => ['required', 'string', 'max:64'],
        ]);

        $uid = preg_replace('/\s+/', '', trim($data['rfid_uid']));
        if ($uid === '' || strlen($uid) < 4) {
            return response()->json([
                'ok' => false,
                'code' => 'INVALID_UID',
                'message' => 'UID kartu tidak valid (minimal 4 karakter).',
            ], 422);
        }

        $petugas = (string) $request->session()->get(self::SESSION_PETUGAS, 'Petugas');

        $result = DB::transaction(function () use ($data, $uid) {
            /** @var Siswa $siswa */
            $siswa = Siswa::query()
                ->whereKey($data['siswa_id'])
                ->lockForUpdate()
                ->with('kelasAktif.kelas:id,nama_kelas')
                ->firstOrFail();

            if ($siswa->status !== 'aktif') {
                return [
                    'status' => 422,
                    'payload' => [
                        'ok' => false,
                        'code' => 'INACTIVE_STUDENT',
                        'message' => "{$siswa->nama} tidak berstatus aktif.",
                    ],
                ];
            }

            if (! empty($siswa->rfid_uid)) {
                return [
                    'status' => 409,
                    'payload' => [
                        'ok' => false,
                        'code' => 'ALREADY_ASSIGNED',
                        'message' => "Mode Aman: {$siswa->nama} sudah memiliki RFID ({$siswa->rfid_uid}). Untuk ganti kartu, gunakan panel Admin.",
                    ],
                ];
            }

            $duplicateSiswa = Siswa::query()
                ->where('rfid_uid', $uid)
                ->where('id', '!=', $siswa->id)
                ->with('kelasAktif.kelas:id,nama_kelas')
                ->first();

            if ($duplicateSiswa) {
                $kelas = $duplicateSiswa->kelasAktif?->kelas?->nama_kelas;
                $labelKelas = $kelas ? " ({$kelas})" : '';

                return [
                    'status' => 409,
                    'payload' => [
                        'ok' => false,
                        'code' => 'DUPLICATE_SISWA',
                        'message' => "Kartu sudah terdaftar atas nama {$duplicateSiswa->nama}{$labelKelas}.",
                    ],
                ];
            }

            $duplicatePegawai = Pegawai::query()
                ->where('rfid_uid', $uid)
                ->first();

            if ($duplicatePegawai) {
                return [
                    'status' => 409,
                    'payload' => [
                        'ok' => false,
                        'code' => 'DUPLICATE_PEGAWAI',
                        'message' => "Kartu sudah terdaftar atas nama Pegawai: {$duplicatePegawai->nama}.",
                    ],
                ];
            }

            $siswa->rfid_uid = $uid;
            $siswa->save();

            return [
                'status' => 200,
                'payload' => [
                    'ok' => true,
                    'message' => "RFID berhasil disimpan untuk {$siswa->nama}.",
                    'student' => $this->formatStudent($siswa),
                ],
            ];
        });

        if ($result['status'] === 200) {
            Log::info('RFID Registrasi Public', [
                'petugas' => $petugas,
                'siswa_id' => $data['siswa_id'],
                'rfid_uid' => $uid,
                'ip' => $request->ip(),
            ]);
        }

        return response()->json($result['payload'], $result['status']);
    }

    /**
     * Cek pemilik kartu RFID tanpa mengubah data.
     */
    public function check(Request $request): JsonResponse
    {
        if (! $this->isUnlocked($request)) {
            return response()->json(['ok' => false, 'message' => 'Sesi terkunci.'], 401);
        }

        $data = $request->validate([
            'rfid_uid' => ['required', 'string', 'max:64'],
        ]);

        $uid = preg_replace('/\s+/', '', trim($data['rfid_uid']));
        if ($uid === '') {
            return response()->json(['ok' => false, 'message' => 'UID kosong.'], 422);
        }

        $siswa = Siswa::query()
            ->where('rfid_uid', $uid)
            ->with('kelasAktif.kelas:id,nama_kelas')
            ->first();

        if ($siswa) {
            return response()->json([
                'ok' => true,
                'found' => true,
                'owner_type' => 'Siswa',
                'owner' => $this->formatStudent($siswa),
            ]);
        }

        $pegawai = Pegawai::query()->where('rfid_uid', $uid)->first();
        if ($pegawai) {
            return response()->json([
                'ok' => true,
                'found' => true,
                'owner_type' => 'Pegawai',
                'owner' => [
                    'id' => $pegawai->id,
                    'nama' => $pegawai->nama,
                    'kelas' => 'Pegawai / Guru',
                    'nisn' => $pegawai->nip ?? '-',
                    'rfid_uid' => $pegawai->rfid_uid,
                ],
            ]);
        }

        return response()->json([
            'ok' => true,
            'found' => false,
            'rfid_uid' => $uid,
        ]);
    }

    private function isUnlocked(Request $request): bool
    {
        if (! $request->session()->get(self::SESSION_UNLOCKED, false)) {
            return false;
        }

        $expiresAt = (int) $request->session()->get(self::SESSION_EXPIRES_AT, 0);
        if ($expiresAt < now()->timestamp) {
            $request->session()->forget([
                self::SESSION_UNLOCKED,
                self::SESSION_PETUGAS,
                self::SESSION_EXPIRES_AT,
            ]);

            return false;
        }

        return true;
    }

    private function expectedPin(): string
    {
        try {
            $settingPin = Setting::query()->value('rfid_registration_pin');
            if (is_string($settingPin) && trim($settingPin) !== '') {
                return trim($settingPin);
            }
        } catch (\Throwable) {
            // Fallback ke env/default jika kolom belum dimigrasi
        }

        return (string) env('RFID_REGISTRATION_PIN', '123456');
    }

    private function kelasOptions(): array
    {
        return Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get(['id', 'nama_kelas', 'tingkat'])
            ->map(fn (Kelas $k) => [
                'id' => $k->id,
                'nama_kelas' => $k->nama_kelas,
                'tingkat' => $k->tingkat,
            ])
            ->values()
            ->all();
    }

    private function resolveSelectedKelasId(Request $request): ?int
    {
        $kelasParam = $request->input('kelas_id') ?? $request->input('kelas');
        if (! $kelasParam) {
            return null;
        }

        if (is_numeric($kelasParam)) {
            return Kelas::query()->whereKey((int) $kelasParam)->value('id');
        }

        return Kelas::query()->where('nama_kelas', (string) $kelasParam)->value('id');
    }

    private function formatStudent(Siswa $siswa): array
    {
        return [
            'id' => $siswa->id,
            'nisn' => $siswa->nisn,
            'nama' => $siswa->nama,
            'kelas' => $siswa->kelasAktif?->kelas?->nama_kelas ?? '-',
            'rfid_uid' => $siswa->rfid_uid,
            'foto_url' => $siswa->foto ? Storage::url($siswa->foto) : null,
        ];
    }
}
