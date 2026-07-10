<?php

namespace App\Console\Commands;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportLegacyPegawai extends Command
{
    protected $signature = 'sigma3:import-legacy-pegawai';

    protected $description = 'Import pegawai dari SIGMA lama';

    public function handle(): int
    {
        $legacyPegawai = DB::connection('legacy')

            ->table('pembina_pelatih')

            ->where('status', 'aktif')

            ->orderBy('nama')

            ->get();

        if ($legacyPegawai->isEmpty()) {

            $this->error('Data pegawai tidak ditemukan.');

            return self::FAILURE;
        }

        $this->info(
            'Total pegawai : ' . $legacyPegawai->count()
        );

        /*
        |--------------------------------------------------------------------------
        | Legacy Override
        |--------------------------------------------------------------------------
        |
        | Koreksi data SIGMA lama yang diketahui tidak sesuai kondisi sebenarnya.
        |
        */

        $override = [

            'YUSRINA KHOIRUN FIRZANAH' => [

                'jenis_pegawai' => 'eksternal',

                'role' => 'pelatih',

            ],

        ];

        $progress = $this->output->createProgressBar(
            $legacyPegawai->count()
        );

        $progress->start();

        $success = 0;
        $skip = 0;
        $failed = 0;

        foreach ($legacyPegawai as $legacy) {

            $jenisPegawai = match ($legacy->jabatan) {

                'pelatih' => 'eksternal',

                default => 'internal',
            };

            $defaultRole = match ($legacy->jabatan) {

                'pelatih' => 'pelatih',

                default => 'guru',
            };

            if (isset($override[$legacy->nama])) {

                $jenisPegawai = $override[$legacy->nama]['jenis_pegawai'];

                $defaultRole = $override[$legacy->nama]['role'];
            }

            if (
                User::where(
                    'username',
                    $legacy->nik_nip
                )->exists()
            ) {

                $skip++;

                $progress->advance();

                continue;
            }

            if (
                Pegawai::where(
                    'nik_nip',
                    $legacy->nik_nip
                )->exists()
            ) {

                $skip++;

                $progress->advance();

                continue;
            }

            try {

                DB::transaction(function () use (

                    $legacy,

                    $jenisPegawai,

                    $defaultRole,

                ) {

                    $user = User::create([

                        'username' => $legacy->nik_nip,

                        'password' => Hash::make(
                            'mtsn3ta123'
                        ),

                        'must_change_password' => true,

                        'status' => 'aktif',

                    ]);

                    $user->assignRole(
                        $defaultRole
                    );

                    Pegawai::create([

                        'user_id' => $user->id,

                        'nama' => $legacy->nama,

                        'nik_nip' => $legacy->nik_nip,

                        'jenis_pegawai' => $jenisPegawai,

                        /*
                        |------------------------------------------------------
                        | Placeholder Biodata
                        |------------------------------------------------------
                        | SIGMA lama tidak memiliki biodata lengkap pegawai.
                        | Akan dilengkapi melalui Super Admin.
                        */

                        'jenis_kelamin' => 'L',

                        'tempat_lahir' => '-',

                        'tanggal_lahir' => now(),

                        'alamat' => '-',

                        'no_hp' => filled($legacy->no_hp)
                            ? $legacy->no_hp
                            : '-',

                        'rfid_uid' => filled($legacy->rfid_uid)
                            ? $legacy->rfid_uid
                            : null,

                        'foto' => null,

                        'ttd_digital' => null,

                        'status' => $legacy->status,

                    ]);
                });

                $success++;
            } catch (\Throwable $e) {

                $failed++;

                $this->newLine();

                $this->error(

                    "{$legacy->nama} : {$e->getMessage()}"

                );
            }

            $progress->advance();
        }

        $progress->finish();

        $this->newLine(2);

        $this->info('==============================');

        $this->info('IMPORT SELESAI');

        $this->info('==============================');

        $this->line('Total    : ' . $legacyPegawai->count());

        $this->line('Berhasil : ' . $success);

        $this->line('Skip     : ' . $skip);

        $this->line('Gagal    : ' . $failed);

        return self::SUCCESS;
    }
}
