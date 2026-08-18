<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;

class GenerateQrKepala extends Command
{
    protected $signature = 'sigma:generate-qr-kepala';
    protected $description = 'Generate signed URL untuk QR verifikasi kepala madrasah';

    public function handle()
    {
        $url = URL::signedRoute('verifikasi.pejabat');

        $this->info('Signed URL untuk QR kartu pelajar:');
        $this->line($url);
    }
}
