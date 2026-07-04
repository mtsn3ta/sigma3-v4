<?php

namespace App\Services\StudentImport;

use App\Models\Siswa;

class ValidateImportService
{
    public function validate(array $rows): array
    {
        $nisnInFile = [];
        $result = [];

        foreach ($rows as $row) {

            $errors = [];

            $nisn = trim($row['nisn'] ?? '');
            $nama = trim($row['nama'] ?? '');
            $jk = strtoupper(trim($row['jenis_kelamin'] ?? ''));

            if ($nisn === '') {
                $errors[] = 'NISN wajib diisi.';
            }

            if ($nama === '') {
                $errors[] = 'Nama wajib diisi.';
            }

            if (! in_array($jk, ['L', 'P'])) {
                $errors[] = 'Jenis Kelamin harus L atau P.';
            }

            if ($nisn !== '') {

                if (isset($nisnInFile[$nisn])) {
                    $errors[] = 'Duplicate NISN pada file.';
                }

                $nisnInFile[$nisn] = true;

                if (Siswa::where('nisn', $nisn)->exists()) {
                    $errors[] = 'NISN sudah ada di database.';
                }
            }

            $row['status'] = empty($errors)
                ? 'valid'
                : 'error';

            $row['errors'] = $errors;

            $result[] = $row;
        }

        return $result;
    }
}
