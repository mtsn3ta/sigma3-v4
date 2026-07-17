<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class StudentTemplateExport implements FromArray
{
    public function array(): array
    {
        return [

            [
                'NISN',
                'Nama',
                'Jenis Kelamin',
            ],

            [
                '0123456789',
                'Ahmad Fauzi',
                'L',
            ],

        ];
    }
}
