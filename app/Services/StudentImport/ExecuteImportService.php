<?php

namespace App\Services\StudentImport;

use App\Models\Siswa;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ExecuteImportService
{
    public function execute(array $rows): array
    {
        $created = 0;

        $skipped = 0;

        DB::transaction(function () use (
            $rows,
            &$created,
            &$skipped,
        ) {

            foreach ($rows as $row) {

                if (
                    Siswa::where('nisn', $row['nisn'])->exists() ||
                    User::where('username', $row['nisn'])->exists()
                ) {

                    $skipped++;

                    continue;
                }

                $user = User::create([

                    'username' => $row['nisn'],

                    'password' => Hash::make('mtsn3ta123'),

                    'must_change_password' => true,

                    'status' => 'aktif',

                ]);

                $user->assignRole('siswa');

                Siswa::create([

                    'user_id' => $user->id,

                    'nisn' => $row['nisn'],

                    'nama' => $row['nama'],

                    'jenis_kelamin' => $row['jenis_kelamin'],

                    'status' => 'aktif',

                ]);

                $created++;
            }
        });

        ActivityLogService::log(

            'Import Siswa',

            sprintf(

                'Berhasil: %d | Dilewati: %d',

                $created,

                $skipped,

            )

        );

        return [

            'created' => $created,

            'skipped' => $skipped,

        ];
    }
}
