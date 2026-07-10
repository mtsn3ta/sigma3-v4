<?php

namespace App\Console\Commands;

use App\Models\AnggotaEkskul;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyAnggotaEkskul extends Command
{
    protected $signature = 'sigma3:import-legacy-anggota-ekskul';

    protected $description = 'Import anggota ekstrakurikuler dari SIGMA lama';

    public function handle(): int
    {
        $legacyAnggota = DB::connection('legacy')
            ->table('anggota_ekskul')
            ->orderBy('id_ekskul')
            ->orderBy('id_siswa')
            ->get();

        if ($legacyAnggota->isEmpty()) {

            $this->error(
                'Data anggota ekstrakurikuler tidak ditemukan.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Total anggota : ' . $legacyAnggota->count()
        );

        $progress = $this->output
            ->createProgressBar(
                $legacyAnggota->count()
            );

        $progress->start();

        $success = 0;
        $skip = 0;
        $failed = 0;

        /*
        |--------------------------------------------------------------------------
        | Import
        |--------------------------------------------------------------------------
        |
        | Diisi pada langkah berikutnya.
        |
        */

        foreach ($legacyAnggota as $legacy) {

            try {

                $legacySiswa = DB::connection('legacy')
                    ->table('siswa')
                    ->where('id_siswa', $legacy->id_siswa)
                    ->first();

                $legacyEkskul = DB::connection('legacy')
                    ->table('ekstrakurikuler')
                    ->where('id_ekskul', $legacy->id_ekskul)
                    ->first();

                if (! $legacySiswa || ! $legacyEkskul) {

                    $failed++;

                    $progress->advance();

                    continue;
                }

                $siswa = Siswa::where(
                    'nisn',
                    $legacySiswa->nis
                )->first();

                $ekskul = Ekstrakurikuler::where(
                    'nama',
                    $legacyEkskul->nama_ekskul
                )->first();

                $tahun = TahunAjaran::aktifId();

                if (! $siswa || ! $ekskul || ! $tahun) {

                    $failed++;

                    $progress->advance();

                    continue;
                }

                if (
                    AnggotaEkskul::where([
                        'siswa_id' => $siswa->id,
                        'ekstrakurikuler_id' => $ekskul->id,
                        'tahun_ajaran_id' => $tahun,
                    ])->exists()
                ) {

                    $skip++;

                    $progress->advance();

                    continue;
                }

                DB::transaction(function () use (
                    $legacy,
                    $siswa,
                    $ekskul,
                    $tahun
                ) {

                    AnggotaEkskul::create([

                        'siswa_id' => $siswa->id,

                        'ekstrakurikuler_id' => $ekskul->id,

                        'tahun_ajaran_id' => $tahun,

                        'tanggal_daftar' => $legacy->tanggal_daftar,

                        'status' => $legacy->status,

                    ]);
                });

                $success++;
            } catch (\Throwable $e) {

                $failed++;

                $this->newLine();

                $this->error(
                    "{$legacy->id_anggota} : {$e->getMessage()}"
                );
            }

            $progress->advance();
        }

        $progress->finish();

        $this->newLine(2);

        $this->info('==============================');

        $this->info('IMPORT SELESAI');

        $this->info('==============================');

        $this->line("Total    : {$legacyAnggota->count()}");

        $this->line("Berhasil : {$success}");

        $this->line("Skip     : {$skip}");

        $this->line("Gagal    : {$failed}");

        return self::SUCCESS;
    }
}
