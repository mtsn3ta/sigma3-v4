<?php

namespace App\Http\Middleware;

use App\Support\SettingHelper;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Support\AnnouncementHelper;
use App\Models\Ekstrakurikuler;
use App\Models\Prestasi;
use App\Support\SiswaHelper;
use App\Models\Galeri;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'settings' => [

                'school_name' => SettingHelper::schoolName(),

                'school_address' => SettingHelper::schoolAddress(),

                'school_phone' => SettingHelper::schoolPhone(),

                'school_email' => SettingHelper::schoolEmail(),

                'logo' => SettingHelper::schoolLogoUrl(),

                'official_logo' => SettingHelper::schoolOfficialLogoUrl(),

                'favicon' => SettingHelper::schoolFaviconUrl(),

                'academic_year' => SettingHelper::tahunAjaranLabel(),

                'principal' => SettingHelper::kepalaMadrasahNama(),
                'principal_photo' => SettingHelper::kepalaMadrasahPhotoUrl(),

            ],
            'urgentAnnouncements' => AnnouncementHelper::urgent(),
            'statistics' => [

                'students' => SiswaHelper::count(),

                'extracurriculars' => Ekstrakurikuler::query()->count(),

                'achievements' => Prestasi::query()->count(),

            ],
            'latestAchievements' => Prestasi::query()

                ->with([
                    'ekstrakurikuler',
                    'siswa',
                ])

                ->latest('tanggal')

                ->take(3)

                ->get()

                ->map(function ($prestasi) {

                    return [

                        'id' => $prestasi->id,

                        'title' => $prestasi->nama_prestasi,

                        'competition' => $prestasi->nama_lomba,

                        'level' => $prestasi->tingkat,

                        'date' => optional($prestasi->tanggal)
                            ->translatedFormat('d M Y'),

                        'photo' => $prestasi->foto
                            ? asset('storage/' . $prestasi->foto)
                            : null,

                        'students' => $prestasi->siswa->count(),

                    ];
                }),
            'featuredExtracurriculars' => Ekstrakurikuler::query()
                ->where('status', 'aktif')
                ->orderBy('nama')
                ->take(3)
                ->get([
                    'id',
                    'nama',
                ]),
            'latestGallery' => Galeri::query()

                ->with('foto')

                ->latest('tanggal')

                ->take(2)

                ->get()

                ->map(function ($gallery) {

                    return [

                        'id' => $gallery->id,

                        'title' => $gallery->judul,

                        'cover' => $gallery->cover
                            ? asset('storage/' . $gallery->cover)
                            : null,

                    ];
                }),
            'attendanceSummary' => SiswaHelper::attendanceSummary(),
        ]);
    }
}
