<?php

namespace App\Console\Commands;

use App\Models\Ekstrakurikuler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyEkstrakurikuler extends Command
{
    protected $signature = 'sigma3:import-legacy-ekstrakurikuler';

    protected $description = 'Import ekstrakurikuler dari SIGMA lama';

    public function handle(): int
    {
        $legacyEkskul = DB::connection('legacy')

            ->table('ekstrakurikuler')

            ->orderBy('nama_ekskul')

            ->get();

        if ($legacyEkskul->isEmpty()) {

            $this->error(
                'Data ekstrakurikuler tidak ditemukan.'
            );

            return self::FAILURE;
        }

        $this->info(
            'Total ekstrakurikuler : ' . $legacyEkskul->count()
        );

        $progress = $this->output
            ->createProgressBar(
                $legacyEkskul->count()
            );

        $progress->start();

        $success = 0;
        $skip = 0;
        $failed = 0;

        foreach ($legacyEkskul as $legacy) {

            if (
                Ekstrakurikuler::where(
                    'nama',
                    $legacy->nama_ekskul
                )->exists()
            ) {

                $skip++;

                $progress->advance();

                continue;
            }

            try {

                DB::transaction(function () use ($legacy) {

                    Ekstrakurikuler::create([

                        'nama' => $legacy->nama_ekskul,

                        'deskripsi' => filled($legacy->deskripsi)
                            ? $legacy->deskripsi
                            : null,

                        'jadwal' => filled($legacy->jadwal)
                            ? collect(explode(',', $legacy->jadwal))
                            ->map(fn($hari) => trim($hari))
                            ->values()
                            ->all()
                            : [],

                        'kuota' => $legacy->kuota,

                        'status' => $legacy->status,

                    ]);
                });

                $success++;
            } catch (\Throwable $e) {

                $failed++;

                $this->newLine();

                $this->error(
                    "{$legacy->nama_ekskul} : {$e->getMessage()}"
                );
            }

            $progress->advance();
        }

        $progress->finish();

        $this->newLine(2);

        $this->info('==============================');

        $this->info('IMPORT SELESAI');

        $this->info('==============================');

        $this->line('Total    : ' . $legacyEkskul->count());

        $this->line('Berhasil : ' . $success);

        $this->line('Skip     : ' . $skip);

        $this->line('Gagal    : ' . $failed);

        return self::SUCCESS;
    }
}
