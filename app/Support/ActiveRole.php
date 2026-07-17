<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class ActiveRole
{
    private const SESSION_KEY = 'active_role';

    /**
     * Ambil role aktif dari session.
     * Kalau belum ada, otomatis set ke role pertama yang dimiliki user.
     */
    public static function get(): ?string
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $current = session(self::SESSION_KEY);

        // Validasi: pastikan role di session masih dimiliki user
        // (antisipasi kalau role dicabut saat session masih aktif)
        if ($current && $user->hasRole($current)) {
            return $current;
        }

        // Fallback: set ke role pertama berdasarkan hierarki
        $role = self::detectPrimaryRole($user);
        self::set($role);

        return $role;
    }

    /**
     * Set role aktif ke session.
     */
    public static function set(string $role): void
    {
        session([self::SESSION_KEY => $role]);
    }

    /**
     * Cek apakah role aktif adalah role tertentu.
     */
    public static function is(string $role): bool
    {
        return self::get() === $role;
    }

    /**
     * Ambil semua role yang dimiliki user saat ini (untuk render tombol switcher).
     */
    public static function availableRoles(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        // Urutan hierarki tampilan di switcher
        $hierarchy = [
            'super_admin',
            'kepala_madrasah',
            'waka_kesiswaan',
            'waka_kurikulum',
            'waka_humas',
            'waka_sarpras',
            'bk',
            'piket',
            'guru',
            'pembina',
            'pelatih',
        ];

        return collect($hierarchy)
            ->filter(fn($role) => $user->hasRole($role))
            ->values()
            ->toArray();
    }

    /**
     * Label tampilan per role untuk UI switcher.
     */
    public static function label(string $role): string
    {
        return match ($role) {
            'super_admin'     => 'Super Admin',
            'kepala_madrasah' => 'Kepala Madrasah',
            'waka_kesiswaan'  => 'Waka Kesiswaan',
            'waka_kurikulum'  => 'Waka Kurikulum',
            'waka_humas'      => 'Waka Humas',
            'waka_sarpras'    => 'Waka Sarpras',
            'bk'              => 'BK',
            'piket'           => 'Guru Piket',
            'guru'            => 'Guru',
            'pembina'         => 'Pembina',
            'pelatih'         => 'Pelatih',
            default           => ucfirst($role),
        };
    }

    /**
     * Icon per role untuk UI switcher.
     */
    public static function icon(string $role): string
    {
        return match ($role) {
            'super_admin'     => 'heroicon-o-shield-check',
            'kepala_madrasah' => 'heroicon-o-building-library',
            'waka_kesiswaan'  => 'heroicon-o-user-group',
            'waka_kurikulum'  => 'heroicon-o-book-open',
            'waka_humas'      => 'heroicon-o-megaphone',
            'waka_sarpras'    => 'heroicon-o-wrench-screwdriver',
            'bk'              => 'heroicon-o-chat-bubble-left-right',
            'piket'           => 'heroicon-o-clipboard-document-check',
            'guru'            => 'heroicon-o-academic-cap',
            'pembina'         => 'heroicon-o-star',
            'pelatih'         => 'heroicon-o-trophy',
            default           => 'heroicon-o-user',
        };
    }

    /**
     * Deteksi role utama berdasarkan hierarki.
     */
    private static function detectPrimaryRole($user): string
    {
        $roles = self::availableRoles();
        return $roles[0] ?? 'guru';
    }
}
