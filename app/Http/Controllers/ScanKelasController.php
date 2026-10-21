<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Services\Presensi\PresensiGuruKelasService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanKelasController extends Controller
{
    public function __construct(
        protected PresensiGuruKelasService $presensiService
    ) {}

    /**
     * Menangani pemindaian QR Code Kelas via HP Guru.
     */
    public function show(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $token = $request->query('token');

        // Jika ada token QR dari pemindaian URL stiker kelas
        if ($token) {
            if (!auth()->check()) {
                session()->put('url.intended', $request->fullUrl());
                return redirect()->route('login')->with('warning', 'Silakan login terlebih dahulu untuk mencatat presensi kelas.');
            }

            $user = auth()->user();
            $pegawai = $user->pegawai;

            if (!$pegawai) {
                return view('scan-kelas.result', [
                    'success' => false,
                    'message' => 'Akun Anda tidak terhubung dengan data Pegawai / Guru.',
                    'kelas' => null,
                    'data' => null,
                ]);
            }

            $kelas = Kelas::where('qr_token', $token)->first();

            if (!$kelas) {
                return view('scan-kelas.result', [
                    'success' => false,
                    'message' => 'QR Code Kelas tidak valid atau belum terdaftar.',
                    'kelas' => null,
                    'data' => null,
                ]);
            }

            $result = $this->presensiService->checkin($pegawai, $kelas, 'qr_code');

            return view('scan-kelas.result', [
                'success' => $result['success'] ?? false,
                'already_recorded' => $result['already_recorded'] ?? false,
                'status_code' => $result['status_code'] ?? null,
                'message' => $result['message'],
                'kelas' => $kelas,
                'data' => $result['data'] ?? null,
            ]);
        }

        // Jika guru membuka menu scan tanpa token (kamera scanner aktif di HP)
        return view('scan-kelas.scanner', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Endpoint API untuk memproses token QR dari scanner kamera browser.
     */
    public function checkinApi(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir. Silakan login kembali.',
            ], 401);
        }

        $user = auth()->user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak terhubung dengan data Guru / Pegawai.',
            ], 403);
        }

        $kelas = Kelas::where('qr_token', $request->input('token'))->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code Kelas tidak dikenali.',
            ], 404);
        }

        $result = $this->presensiService->checkin($pegawai, $kelas, 'qr_code');

        return response()->json($result);
    }
}
