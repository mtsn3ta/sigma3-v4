<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Inertia\Inertia;
use Inertia\Response;

class ParentPortalController extends Controller
{
    public function __invoke(
        string $nisn,
    ): Response {

        $student = Siswa::with([
            'kelasAktif.kelas',
        ])
            ->where('nisn', $nisn)
            ->firstOrFail();

        return Inertia::render(
            'ParentPortal/Index',
            [

                'student' => [

                    'id' => $student->id,

                    'nisn' => $student->nisn,

                    'nama' => $student->nama,

                    'foto' => $student->foto
                        ? asset('storage/' . $student->foto)
                        : null,

                    'kelas' => $student->nama_kelas_aktif,

                    'status' => $student->status,

                ],

            ]
        );
    }
}
