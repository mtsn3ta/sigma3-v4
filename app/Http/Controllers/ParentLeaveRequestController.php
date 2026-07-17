<?php

namespace App\Http\Controllers;

use App\Models\ParentLeaveRequest;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ParentLeaveRequestController extends Controller
{
    public function store(
        Request $request,
        string $nisn,
    ) {

        $siswa = Siswa::where(
            'nisn',
            $nisn
        )->firstOrFail();

        $validated = $request->validate([

            'jenis' => ['required', 'in:sakit,izin'],

            'tanggal_mulai' => ['required', 'date'],

            'tanggal_selesai' => ['required', 'date'],

            'punya_surat_dokter' => ['required', 'boolean'],

            'keterangan' => ['nullable'],

            'signature' => ['required'],

            'selfie' => ['required', 'image'],

            'surat_dokter' => ['nullable', 'file'],
            'nama_pengirim' => ['required', 'string', 'max:100'],

            'hubungan' => ['required', 'in:ayah,ibu,wali'],

        ]);

        $selfie = $request->file('selfie')
            ->store('parent-leave/selfie', 'public');

        $doctorLetter = null;

        if ($request->hasFile('surat_dokter')) {

            $doctorLetter = $request->file('surat_dokter')
                ->store('parent-leave/surat', 'public');
        }

        ParentLeaveRequest::create([

            'siswa_id' => $siswa->id,

            'jenis' => $validated['jenis'],

            'tanggal_mulai' => $validated['tanggal_mulai'],

            'tanggal_selesai' => $validated['tanggal_selesai'],

            'keterangan' => $validated['keterangan'],

            'punya_surat_dokter' => $validated['punya_surat_dokter'],

            'doctor_letter' => $doctorLetter,

            'parent_selfie' => $selfie,

            'parent_signature' => $validated['signature'],

            'status' => 'pending',
            'nama_pengirim' => $validated['nama_pengirim'],

            'hubungan' => $validated['hubungan'],

        ]);

        return back()->with(
            'success',
            'Pengajuan berhasil dikirim.'
        );
    }
}
