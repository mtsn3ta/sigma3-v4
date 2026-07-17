<?php

namespace App\Services\Printer;

use Mike42\Escpos\PrintConnectors\PrintConnector;

class Sigma3PrintConnector implements PrintConnector
{
    private string $buffer = '';

    // FUNGSI INI YANG DIMINTA OLEH LARAVEL/ESCPOS
    public function __destruct()
    {
    }

    public function write(string $data): void
    {
        $this->buffer .= $data;
    }

    public function read(int $len): string
    {
        return '';
    }

    public function finalize(): void
    {
        // TAMBAHKAN urlencode() DI SINI
        $encoded = urlencode(base64_encode($this->buffer));
        echo "sigma3print://cetak?data={$encoded}";
    }
}