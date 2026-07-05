<?php

namespace App\Services\StudentProfile;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdatePasswordService
{
    public function execute(
        User $user,
        string $currentPassword,
        string $newPassword,
    ): void {

        if (! Hash::check(
            $currentPassword,
            $user->password,
        )) {

            throw ValidationException::withMessages([

                'current_password' => 'Password lama tidak sesuai.',

            ]);
        }
        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password baru tidak boleh sama dengan password lama.',
            ]);
        }

        $user->update([

            'password' => Hash::make($newPassword),

            'must_change_password' => false,

        ]);

        ActivityLogService::log(
            'Ganti Password',
            sprintf(
                '%s mengganti password.',
                $user->username,
            ),
        );
    }
}
