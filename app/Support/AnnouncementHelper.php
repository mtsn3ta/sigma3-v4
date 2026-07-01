<?php

namespace App\Support;

use App\Models\Pengumuman;

class AnnouncementHelper
{
    public static function urgent()
    {
        return Pengumuman::query()

            ->where('status', 'publish')

            ->where('is_pinned', true)

            ->whereDate('tanggal_mulai', '<=', today())

            ->whereDate('tanggal_selesai', '>=', today())

            ->latest()

            ->get([
                'id',
                'judul',
                'tipe',
                'lampiran',
            ]);
    }
}
