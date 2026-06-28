<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NavigationHelper
{
    /**
     * Menentukan Navigation Group berdasarkan prioritas role.
     */
    public static function group(array $groups, ?string $default = null): ?string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return $default ?? array_values($groups)[0] ?? null;
        }

        foreach ($groups as $role => $group) {

            if ($user->hasRole($role)) {
                return $group;
            }
        }

        return $default ?? array_values($groups)[0] ?? null;
    }
}
