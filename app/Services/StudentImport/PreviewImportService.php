<?php

namespace App\Services\StudentImport;

use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;

class PreviewImportService
{
    public function preview(string $path)
    {
        $import = new StudentImport();

        Excel::import($import, $path);

        return $import->rows;
    }
}
