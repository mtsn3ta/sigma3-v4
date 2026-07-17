<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\Pegawai;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Schema;

class SettingHelper
{
    protected static ?Setting $setting = null;

    protected static function setting(): ?Setting
{
    if (! Schema::hasTable('settings')) {
        return null;
    }

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
| RFID School
|--------------------------------------------------------------------------
*/

    public static function rfidSchoolEnabled(): bool
    {
        return (bool) self::setting()?->rfid_school_enabled;
    }

    public static function rfidSchoolCheckinStart(): string
    {
        return self::setting()?->rfid_school_checkin_start ?? '06:00:00';
    }

    public static function rfidSchoolCheckinEnd(): string
    {
        return self::setting()?->rfid_school_checkin_end ?? '07:10:00';
    }

    public static function rfidSchoolCheckoutStart(): string
    {
        return self::setting()?->rfid_school_checkout_start ?? '14:30:00';
    }

    public static function rfidSchoolCheckoutEnd(): string
    {
        return self::setting()?->rfid_school_checkout_end ?? '17:00:00';
    }

    /*
|--------------------------------------------------------------------------
| RFID Ibadah
|--------------------------------------------------------------------------
*/

    public static function rfidIbadahEnabled(): bool
    {
        return (bool) self::setting()?->rfid_ibadah_enabled;
    }

    public static function rfidDhuhaStart(): string
    {
        return self::setting()?->rfid_ibadah_dhuha_start ?? '06:40:00';
    }

    public static function rfidDhuhaEnd(): string
    {
        return self::setting()?->rfid_ibadah_dhuha_end ?? '07:00:00';
    }

    public static function rfidDzuhurStart(): string
    {
        return self::setting()?->rfid_ibadah_dzuhur_start ?? '11:45:00';
    }

    public static function rfidDzuhurEnd(): string
    {
        return self::setting()?->rfid_ibadah_dzuhur_end ?? '12:20:00';
    }

    public static function rfidDisableFriday(): bool
    {
        return (bool) self::setting()?->rfid_ibadah_disable_friday;
    }

    /*
|--------------------------------------------------------------------------
| RFID General
|--------------------------------------------------------------------------
*/

    public static function rfidUidLockSeconds(): int
    {
        return (int) (self::setting()?->rfid_general_uid_lock_seconds ?? 2);
    }

    public static function rfidEnableWebNfc(): bool
    {
        return (bool) self::setting()?->rfid_general_enable_web_nfc;
    }

    public static function rfidEnableUsbReader(): bool
    {
        return (bool) self::setting()?->rfid_general_enable_usb_reader;
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
