<?php

namespace App\Support;

use App\Models\IzinKeluar;
use App\Models\KehadiranIbadah;
use App\Models\KehadiranSekolah;
use Illuminate\Support\Collection;

class AttendanceStatusHelper
{
    public static function make(
        ?KehadiranSekolah $sekolah,
        ?KehadiranIbadah $dhuha,
        ?KehadiranIbadah $dzuhur,
        ?Collection $pembelajaran = null,
        ?IzinKeluar $izinKeluar = null,
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Sekolah
        |--------------------------------------------------------------------------
        */

        if ($sekolah?->status === 'izin') {
            return self::badge('izin', 'Izin', 'primary', '🟣');
        }

        if ($sekolah?->status === 'sakit') {
            return self::badge('sakit', 'Sakit', 'gray', '🟤');
        }

        if ($sekolah?->status === 'alfa') {
            return self::badge('tidak_hadir', 'Tidak Hadir', 'danger', '🔴');
        }

        /*
|--------------------------------------------------------------------------
| Pembelajaran
|--------------------------------------------------------------------------
*/

        if ($pembelajaran && $pembelajaran->isNotEmpty()) {

            if ($pembelajaran->contains('status', 'lomba')) {
                return self::badge(
                    'lomba',
                    'Lomba',
                    'warning',
                    '🏆',
                );
            }

            if ($pembelajaran->contains('status', 'dispensasi')) {
                return self::badge(
                    'dispensasi',
                    'Dispensasi',
                    'info',
                    '🟦',
                );
            }

            if ($pembelajaran->contains('status', 'ke_bk')) {
                return self::badge(
                    'ke_bk',
                    'Ke BK',
                    'danger',
                    '🟠',
                );
            }

            if ($pembelajaran->contains('status', 'ke_uks')) {
                return self::badge(
                    'ke_uks',
                    'Ke UKS',
                    'secondary',
                    '🟡',
                );
            }

            if ($pembelajaran->contains('status', 'bolos')) {
                return self::badge(
                    'bolos',
                    'Bolos',
                    'danger',
                    '⛔',
                );
            }

            if ($pembelajaran->contains('status', 'lainnya')) {
                return self::badge(
                    'lainnya',
                    'Lainnya',
                    'secondary',
                    '📄',
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Izin Keluar
        |--------------------------------------------------------------------------
        */

        if ($izinKeluar?->isKeluar()) {

            return self::badge(
                'izin_keluar',
                'Izin Keluar',
                'info',
                '🚪',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ibadah
        |--------------------------------------------------------------------------
        */

        if ($dhuha?->status === 'menstruasi') {

            return self::badge(
                'menstruasi',
                'Menstruasi',
                'pink',
                '🌸',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tidak hadir
        |--------------------------------------------------------------------------
        */

        if (! $sekolah) {

            return self::badge(
                'tidak_hadir',
                'Tidak Hadir',
                'danger',
                '🔴',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Belum Pulang
        |--------------------------------------------------------------------------
        */

        if (! $sekolah->jam_pulang) {

            return self::badge(
                'belum_pulang',
                'Belum Pulang',
                'info',
                '🔵',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Belum Dzuhur
        |--------------------------------------------------------------------------
        */

        if (! $dzuhur) {

            return self::badge(
                'belum_dzuhur',
                'Belum Dzuhur',
                'warning',
                '🟠',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Belum Dhuha
        |--------------------------------------------------------------------------
        */

        if (! $dhuha) {

            return self::badge(
                'belum_dhuha',
                'Belum Dhuha',
                'warning',
                '🟠',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Terlambat
        |--------------------------------------------------------------------------
        */

        if ($sekolah->status === 'terlambat') {

            return self::badge(
                'terlambat',
                'Terlambat',
                'warning',
                '🟡',
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Lengkap
        |--------------------------------------------------------------------------
        */

        return self::badge(
            'hadir',
            'Hadir Lengkap',
            'success',
            '🟢',
        );
    }

    protected static function badge(
        string $code,
        string $label,
        string $color,
        string $icon,
    ): array {

        return [

            'code' => $code,

            'label' => $label,

            'color' => $color,

            'icon' => $icon,

        ];
    }
}
