<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToCollection, WithHeadingRow
{
    /**
     * Data hasil pembacaan Excel.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $rows = [];

    public function collection(Collection $rows): void
    {
        // PERBAIKAN: Setiap `$row` di dalam koleksi utama sudah berbentuk array,
        // bukan class Collection lagi karena Anda menggunakan ToCollection standar.
        $this->rows = $rows
            ->map(fn($row) => is_array($row) ? $row : (method_exists($row, 'toArray') ? $row->toArray() : (array) $row))
            ->values()
            ->all();
    }
}
