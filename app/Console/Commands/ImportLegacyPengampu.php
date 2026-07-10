<?php

namespace App\Console\Commands;

use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerPengampu;
use App\Models\Pegawai;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyPengampu extends Command
{
    protected $signature = 'sigma3:import-legacy-pengampu';

    protected $description = 'Import pengampu ekstrakurikuler dari SIGMA lama';

    public function handle(): int
    {
        $legacyPengampu = DB::connection('legacy')

            ->table('ekskul_pengampu')

            ->orderBy('id_ekskul')

            ->get();

        if ($legacyPengampu->isEmpty()) {

            $this->error(
                'Data pengampu tidak ditemukan.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Total relasi : ' . $legacyPengampu->count()
        );

        $progress = $this->output->createProgressBar(
            $legacyPengampu->count()
        );

        $progress->start();

        $success = 0;
        $skip = 0;
        $failed = 0;

        foreach ($legacyPengampu as $legacy) {

            try {

                /*
                |--------------------------------------------------------------------------
                | Ambil Ekstrakurikuler Legacy
                |--------------------------------------------------------------------------
                */

                $legacyEkskul = DB::connection('legacy')

                    ->table('ekstrakurikuler')

                    ->where(
                        'id_ekskul',
                        $legacy->id_ekskul
                    )

                    ->first();

                if (! $legacyEkskul) {

                    $failed++;

                    $progress->advance();

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Ambil Pembina/Pelatih Legacy
                |--------------------------------------------------------------------------
                */

                $legacyPegawai = DB::connection('legacy')

                    ->table('pembina_pelatih')

                    ->where(
                        'id_pembina',
                        $legacy->id_pembina
                    )

                    ->first();

                if (! $legacyPegawai) {

                    $failed++;

                    $progress->advance();

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Mapping SIGMA3
                |--------------------------------------------------------------------------
                */

                $ekskul = Ekstrakurikuler::where(
                    'nama',
                    $legacyEkskul->nama_ekskul
                )->first();

                $pegawai = Pegawai::where(
                    'nik_nip',
                    $legacyPegawai->nik_nip
                )->first();

                if (! $ekskul || ! $pegawai) {

                    $failed++;

                    $progress->advance();

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Skip Duplicate
                |--------------------------------------------------------------------------
                */

                if (
                    EkstrakurikulerPengampu::where([
                        'ekstrakurikuler_id' => $ekskul->id,
                        'pegawai_id' => $pegawai->id,
                        'role' => $legacy->role,
                    ])->exists()
                ) {

                    $skip++;

                    $progress->advance();

                    continue;
                }

                DB::transaction(function () use (

                    $ekskul,

                    $pegawai,

                    $legacy

                ) {

                    EkstrakurikulerPengampu::create([

                        'ekstrakurikuler_id' => $ekskul->id,

                        'pegawai_id' => $pegawai->id,

                        'role' => $legacy->role,

                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Assign Role Otomatis
                    |--------------------------------------------------------------------------
                    */

                    $pegawai->user?->assignRole(
                        $legacy->role
                    );
                });

                $success++;
            } catch (\Throwable $e) {

                $failed++;

                $this->newLine();

                $this->error(
                    $e->getMessage()
                );
            }

            $progress->advance();
        }

        $progress->finish();

        $this->newLine(2);

        $this->info('==============================');

        $this->info('IMPORT SELESAI');

        $this->info('==============================');

        $this->line("Total    : {$legacyPengampu->count()}");
        $this->line("Berhasil : {$success}");
        $this->line("Skip     : {$skip}");
        $this->line("Gagal    : {$failed}");

        return self::SUCCESS;
    }
}
