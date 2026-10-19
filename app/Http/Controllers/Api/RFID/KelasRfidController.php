<?php

namespace App\Http\Controllers\Api\RFID;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pegawai;
use App\Services\Presensi\PresensiGuruKelasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KelasRfidController extends Controller
{
    public function __construct(
        protected PresensiGuruKelasService $presensiService
    ) {}

    /**
     * Endpoint API untuk tap kartu RFID di kelas.
     * Menerima payload:
     * - uid: RFID UID kartu guru (string)
     * - kelas_id: ID kelas (integer) ATAU token: QR token kelas (string)
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'uid' => 'required|string',
            'kelas_id' => 'nullable|integer',
            'token' => 'nullable|string',
        ]);

        $uid = trim($request->input('uid'));

        // Cari data pegawai berdasarkan RFID UID
        $pegawai = Pegawai::where('rfid_uid', $uid)->first();

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => "Kartu RFID ({$uid}) belum terdaftar pada data guru/pegawai.",
                'tts' => 'Kartu belum terdaftar.',
            ], 404);
        }

        // Cari Kelas
        $kelas = null;
        if ($request->filled('kelas_id')) {
            $kelas = Kelas::find($request->input('kelas_id'));
        } elseif ($request->filled('token')) {
            $kelas = Kelas::where('qr_token', $request->input('token'))->first();
        }

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Ruang kelas tidak valid atau belum dipilih.',
                'tts' => 'Kelas tidak valid.',
            ], 400);
        }

        $result = $this->presensiService->checkin($pegawai, $kelas, 'rfid');

        // Tambahkan feedback suara TTS untuk kiosk reader
        if ($result['success']) {
            $greeting = ($pegawai->jenis_kelamin === 'L' || str_contains(strtolower($pegawai->nama), 'bapak')) ? 'Bapak' : 'Ibu';
            $result['tts'] = "Terima kasih {$greeting} {$pegawai->nama}, selamat mengajar di Kelas {$kelas->nama_kelas}.";
        } else {
            $result['tts'] = $result['message'];
        }

        return response()->json($result);
    }
}
