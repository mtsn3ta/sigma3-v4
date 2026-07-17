<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Galeri::query()
            ->with([
                'foto',
                'ekstrakurikuler',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where(
                'judul',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tahun
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun')) {

            $query->whereYear(
                'tanggal',
                $request->tahun
            );
        }

        $albums = $query

            ->latest('tanggal')

            ->paginate(12)

            ->withQueryString();

        return Inertia::render('Galeri', [

            'albums' => $albums->through(function (Galeri $album) {

                return [

                    'id' => $album->id,

                    'title' => $album->judul,

                    'date' => optional($album->tanggal)
                        ->translatedFormat('d M Y'),

                    'year' => optional($album->tanggal)
                        ->format('Y'),

                    'cover' => $album->cover
                        ? asset('storage/' . $album->cover)
                        : null,
                    'photo_count' => $album->foto->count(),
                    'photos' => $album->foto
                        ->map(fn($foto) => [

                            'id' => $foto->id,

                            'url' => asset('storage/' . $foto->foto),

                            'caption' => $foto->keterangan,

                            'order' => $foto->urutan,

                        ])
                        ->values(),
                    'category' => $album->kategori_label,
                    'description' => $album->deskripsi,
                    'extracurricular' => $album
                        ->ekstrakurikuler
                        ?->nama,

                ];
            }),

            'filters' => [

                'search' => $request->string('search'),

                'tahun' => $request->string('tahun'),

            ],

            'years' => Galeri::query()

                ->selectRaw('YEAR(tanggal) as tahun')

                ->distinct()

                ->orderByDesc('tahun')

                ->pluck('tahun'),

        ]);
    }
}
