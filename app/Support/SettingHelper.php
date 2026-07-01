<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\Pegawai;
use App\Models\TahunAjaran;

class SettingHelper
{
    protected static ?Setting $setting = null;

    protected static function setting(): ?Setting
    {
        if (self::$setting === null) {

            self::$setting = Setting::query()

                ->with([
                    'kepalaAktif',
                    'tahunAjaranAktif',
                ])

                ->first();
        }

        return self::$setting;
    }

    /*
    |--------------------------------------------------------------------------
    | Identitas Madrasah
    |--------------------------------------------------------------------------
    */

    public static function schoolName(): string
    {
        return self::setting()?->nama_madrasah ?? '-';
    }

    public static function schoolAddress(): string
    {
        return self::setting()?->alamat ?? '-';
    }

    public static function schoolPhone(): string
    {
        return self::setting()?->telepon ?? '-';
    }

    public static function schoolEmail(): string
    {
        return self::setting()?->email ?? '-';
    }

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    */

    public static function schoolLogo(): ?string
    {
        return self::setting()?->logo;
    }

    public static function schoolOfficialLogo(): ?string
    {
        return self::setting()?->logo_resmi;
    }

    public static function schoolFavicon(): ?string
    {
        return self::setting()?->favicon;
    }
    public static function schoolLogoUrl(): ?string
    {
        $logo = self::schoolLogo();

        return $logo
            ? asset('storage/' . $logo)
            : null;
    }

    public static function schoolOfficialLogoUrl(): ?string
    {
        $logo = self::schoolOfficialLogo();

        return $logo
            ? asset('storage/' . $logo)
            : null;
    }

    public static function schoolFaviconUrl(): ?string
    {
        $favicon = self::schoolFavicon();

        return $favicon
            ? asset('storage/' . $favicon)
            : null;
    }
    /*
|--------------------------------------------------------------------------
| Foto Kepala Madrasah
|--------------------------------------------------------------------------
*/

    public static function kepalaMadrasahPhoto(): ?string
    {
        return self::setting()?->kepala_madrasah_photo;
    }

    public static function kepalaMadrasahPhotoUrl(): ?string
    {
        $photo = self::kepalaMadrasahPhoto();

        return $photo
            ? asset('storage/' . $photo)
            : null;
    }
    /*
    |--------------------------------------------------------------------------
    | Kepala Madrasah
    |--------------------------------------------------------------------------
    */

    public static function kepalaMadrasah(): ?Pegawai
    {
        return self::setting()?->kepalaAktif;
    }

    public static function kepalaMadrasahNama(): string
    {
        return self::setting()?->kepalaAktif?->nama ?? '-';
    }

    /*
    |--------------------------------------------------------------------------
    | Tahun Ajaran
    |--------------------------------------------------------------------------
    */

    public static function tahunAjaran(): ?TahunAjaran
    {
        return self::setting()?->tahunAjaranAktif;
    }

    public static function tahunAjaranLabel(): string
    {
        return self::setting()?->tahunAjaranAktif?->tahun ?? '-';
    }

    /*
    |--------------------------------------------------------------------------
    | Lainnya
    |--------------------------------------------------------------------------
    */

    public static function exists(): bool
    {
        return self::setting() !== null;
    }

    public static function refresh(): void
    {
        self::$setting = null;
    }
}
