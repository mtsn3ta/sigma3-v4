<?php

namespace App\Support;

use App\Models\TahunAjaran;

class TahunAjaranHelper
{
    public static function options(): array
    {
        return TahunAjaran::query()

            ->orderByDesc('tahun')

            ->pluck('tahun', 'id')

            ->toArray();
    }

    public static function aktif(): ?TahunAjaran
    {
        return TahunAjaran::query()

            ->where('aktif', true)

            ->first();
    }

    public static function aktifId(): ?int
    {
        return self::aktif()?->id;
    }
}
