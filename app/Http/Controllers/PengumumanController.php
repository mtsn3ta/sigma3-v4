<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Inertia\Inertia;
use Inertia\Response;

class PengumumanController extends Controller
{
    public function __invoke(
        Pengumuman $pengumuman,
    ): Response {

        abort_unless(
            $pengumuman->status === 'publish',
            404
        );

        $pengumuman->load([
            'ekstrakurikuler',
            'createdBy',
        ]);

        return Inertia::render(
            'Pengumuman/Show',
            [

                'announcement' => [

                    'id' => $pengumuman->id,

                    'title' => $pengumuman->judul,

                    'content' => $pengumuman->isi,

                    'type' => $pengumuman->tipe,

                    'type_label' => $pengumuman->tipe_label,

                    'attachment' => $pengumuman->lampiran
                        ? asset('storage/' . $pengumuman->lampiran)
                        : null,

                    'start_date' => optional(
                        $pengumuman->tanggal_mulai
                    )->translatedFormat('d F Y'),

                    'end_date' => optional(
                        $pengumuman->tanggal_selesai
                    )->translatedFormat('d F Y'),

                    'status' => $pengumuman->status_label,

                    'pinned' => $pengumuman->is_pinned,

                    'extracurricular' => $pengumuman
                        ->ekstrakurikuler?->nama,

                    'author' => $pengumuman
                        ->createdBy?->name,

                ],

                'latestAnnouncements' => Pengumuman::query()

                    ->where('status', 'publish')

                    ->whereKeyNot($pengumuman->id)

                    ->latest('tanggal_mulai')

                    ->take(5)

                    ->get()

                    ->map(fn($item) => [

                        'id' => $item->id,

                        'title' => $item->judul,

                        'type' => $item->tipe,

                        'date' => optional(
                            $item->tanggal_mulai
                        )->translatedFormat('d M Y'),

                    ]),

            ]
        );
    }
}
