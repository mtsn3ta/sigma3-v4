<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PrestasiController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Prestasi::query()
            ->with([
                'ekstrakurikuler',
                'siswa',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_prestasi', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_lomba', 'like', '%' . $request->search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tingkat
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tingkat')) {
            $query->where(
                'tingkat',
                $request->tingkat
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Ekstrakurikuler
        |--------------------------------------------------------------------------
        */

        if ($request->filled('ekstrakurikuler')) {
            $query->where(
                'ekstrakurikuler_id',
                $request->ekstrakurikuler
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {
            $query->whereYear(
                'tanggal',
                $request->tahun
            );
        }

        $prestasi = $query
            ->latest('tanggal')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Prestasi', [

            'achievements' => $prestasi->through(function (Prestasi $prestasi) {

                return [

                    'id' => $prestasi->id,

                    'title' => $prestasi->nama_prestasi,

                    'competition' => $prestasi->nama_lomba,

                    'level' => ucfirst($prestasi->tingkat),

                    'date' => optional($prestasi->tanggal)
                        ->translatedFormat('d M Y'),

                    'photo' => $prestasi->foto
                        ? asset('storage/' . $prestasi->foto)
                        : null,

                    'description' => $prestasi->keterangan,

                    'extracurricular' => $prestasi->ekstrakurikuler?->nama,

                    'participant_count' => $prestasi->siswa->count(),

                    'participant_names' => $prestasi->siswa
                        ->take(2)
                        ->pluck('nama')
                        ->implode(', '),

                    'participants' => $prestasi->siswa
                        ->map(fn($siswa) => [
                            'id' => $siswa->id,
                            'nama' => $siswa->nama,
                        ])
                        ->values(),

                ];
            }),

            'filters' => [

                'search' => $request->string('search'),

                'tingkat' => $request->string('tingkat'),

                'ekstrakurikuler' => $request->integer('ekstrakurikuler'),

                'tahun' => $request->string('tahun'),

            ],

            'levels' => [

                'kecamatan' => 'Kecamatan',

                'kabupaten' => 'Kabupaten',

                'karesidenan' => 'Karesidenan',

                'provinsi' => 'Provinsi',

                'nasional' => 'Nasional',

                'internasional' => 'Internasional',

            ],

            'years' => Prestasi::query()
                ->selectRaw('YEAR(tanggal) as tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun'),

            'extracurriculars' => Ekstrakurikuler::query()
                ->orderBy('nama')
                ->pluck('nama', 'id'),

        ]);
    }
}
