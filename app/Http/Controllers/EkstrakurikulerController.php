<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EkstrakurikulerController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Ekstrakurikuler::query()
            ->with([
                'pengampu.pegawai',
                'anggotaEkskul',
                'prestasi',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(
                'nama',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $ekstrakurikuler = $query
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Ekstrakurikuler', [

            'extracurriculars' => $ekstrakurikuler->through(function (Ekstrakurikuler $ekskul) {

                $pembina = $ekskul->pengampu
                    ->where('role', 'pembina')
                    ->pluck('pegawai.nama')
                    ->values();

                $pelatih = $ekskul->pengampu
                    ->where('role', 'pelatih')
                    ->pluck('pegawai.nama')
                    ->values();

                return [

                    'id' => $ekskul->id,

                    'name' => $ekskul->nama,

                    'description' => $ekskul->deskripsi,

                    'status' => ucfirst($ekskul->status),

                    'schedule' => $ekskul->jadwal,

                    'members' => $ekskul->anggotaEkskul()->where('status', 'aktif')->count(),

                    'achievement_count' => $ekskul->prestasi()->count(),

                    'achievements' => $ekskul->prestasi
                        ->sortByDesc('tanggal')
                        ->take(5)
                        ->map(fn($prestasi) => [

                            'title' => $prestasi->nama_prestasi,

                            'competition' => $prestasi->nama_lomba,

                            'date' => optional($prestasi->tanggal)
                                ->translatedFormat('d M Y'),

                        ])
                        ->values(),

                    'coaches' => $pelatih,

                    'supervisors' => $pembina,

                ];
            }),

            'filters' => [

                'search' => $request->string('search'),

                'status' => $request->string('status'),

            ],

        ]);
    }
}
