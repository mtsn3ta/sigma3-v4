<?php

namespace App\Support;

use App\Models\EkstrakurikulerPengampu;
use App\Models\User;

class RoleHelper
{
    /*
    |--------------------------------------------------------------------------
    | Role Manual (Form Pegawai)
    |--------------------------------------------------------------------------
    */

    protected const MANUAL_ROLES = [

        'guru',

        'kepala_madrasah',

        'waka_kesiswaan',
        'waka_kurikulum',
        'waka_humas',
        'waka_sarpras',

        'bk',
        'piket',

    ];

    /*
    |--------------------------------------------------------------------------
    | Role Otomatis (Pengampu)
    |--------------------------------------------------------------------------
    */

    protected const AUTO_ROLES = [

        'pembina',

        'pelatih',

    ];

    /*
    |--------------------------------------------------------------------------
    | Sinkronisasi Role Pegawai
    |--------------------------------------------------------------------------
    */

    public static function syncPegawaiRoles(
        User $user,
        string $jenisPegawai,
        array $selectedRoles = [],
    ): void {

        $selectedRoles = array_values($selectedRoles);

        $wakaRoles = [

            'waka_kesiswaan',
            'waka_kurikulum',
            'waka_humas',
            'waka_sarpras',

        ];

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        if (

            in_array('kepala_madrasah', $selectedRoles)

            &&

            count($selectedRoles) > 1

        ) {

            abort(
                422,
                'Kepala Madrasah tidak dapat memiliki role lain.'
            );
        }

        if (

            count(

                array_intersect(
                    $selectedRoles,
                    $wakaRoles
                )

            ) > 1

        ) {

            abort(
                422,
                'Hanya boleh memilih satu role Waka.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Role Manual
        |--------------------------------------------------------------------------
        */

        if ($jenisPegawai === 'eksternal') {

            $manualRoles = [];
        } else {

            if (in_array('kepala_madrasah', $selectedRoles)) {

                $manualRoles = [

                    'kepala_madrasah',

                ];
            } else {

                $manualRoles = array_merge(

                    ['guru'],

                    $selectedRoles

                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pertahankan Role Otomatis
        |--------------------------------------------------------------------------
        */

        $autoRoles = $user->roles

            ->pluck('name')

            ->filter(

                fn($role) => in_array(
                    $role,
                    self::AUTO_ROLES
                )

            )

            ->values()

            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Sync
        |--------------------------------------------------------------------------
        */

        $user->syncRoles(

            array_unique(

                array_merge(

                    $manualRoles,

                    $autoRoles

                )

            )

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Tambah Role Pengampu
    |--------------------------------------------------------------------------
    */

    public static function assignPengampuRole(
        User $user,
        string $role,
    ): void {

        if (

            in_array(

                $role,

                self::AUTO_ROLES

            )

        ) {

            $user->assignRole($role);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Hapus Role Pengampu Jika Sudah Tidak Dipakai
    |--------------------------------------------------------------------------
    */

    public static function removePengampuRoleIfUnused(
        User $user,
        int $pegawaiId,
        string $role,
    ): void {

        $masihDigunakan = EkstrakurikulerPengampu::query()

            ->where(
                'pegawai_id',
                $pegawaiId
            )

            ->where(
                'role',
                $role
            )

            ->exists();

        if (! $masihDigunakan) {

            $user->removeRole($role);
        }
    }
}
