<?php

namespace App\Support;

use Carbon\Carbon;

class JamPelajaranHelper
{
    public static function maxJam(Carbon $tanggal): int
    {
        return match ($tanggal->dayOfWeekIso) {

            1 => 8,

            2,
            3,
            4 => 9,

            5 => 5,

            6 => 8,

            default => 0,
        };
    }

    public static function parse(string $jamKe): array
    {
        $jamKe = trim($jamKe);

        if (str_contains($jamKe, '-')) {

            [$awal, $akhir] = explode('-', $jamKe);

            return range(
                (int) $awal,
                (int) $akhir
            );
        }

        return [
            (int) $jamKe,
        ];
    }

    public static function sortKey(string $jamKe): int
    {
        return self::parse($jamKe)[0];
    }
}
