<?php

namespace App\Http\Controllers;

use App\Support\SettingHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function pejabat(Request $request)
    {
        $valid = $request->hasValidSignature();

        $kepala = SettingHelper::kepalaMadrasah();

        return view('verifikasi.pejabat', [
            'valid'      => $valid,
            'kepala'     => $kepala,
            'waktu'      => Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i:s'),
            'schoolName' => SettingHelper::schoolName(),
            'fotoKepala' => $kepala?->foto ? asset('storage/' . $kepala->foto) : SettingHelper::kepalaMadrasahPhotoUrl(),
            'logo'       => SettingHelper::schoolLogoUrl(),
            'logoResmi'  => SettingHelper::schoolOfficialLogoUrl(),
        ]);
    }
}
