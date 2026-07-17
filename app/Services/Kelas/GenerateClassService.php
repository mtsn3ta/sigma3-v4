<?php

namespace App\Services\Kelas;

use App\Models\Kelas;

class GenerateClassService
{
    public function generate(
        int $tingkat,
        string $format,
        string $from,
        string $to,
    ): array {

        $created = 0;

        $skipped = 0;

        $classes = $this->buildClassNames(
            $tingkat,
            $format,
            $from,
            $to,
        );

        foreach ($classes as $namaKelas) {

            $exists = Kelas::query()
                ->where('nama_kelas', $namaKelas)
                ->exists();

            if ($exists) {

                $skipped++;

                continue;
            }

            Kelas::create([
                'nama_kelas' => $namaKelas,
                'tingkat'    => $tingkat,
                'status'     => 'aktif',
            ]);

            $created++;
        }

        return [

            'created' => $created,

            'skipped' => $skipped,

        ];
    }

    protected function buildClassNames(
        int $tingkat,
        string $format,
        string $from,
        string $to,
    ): array {

        $classes = [];

        if ($format === 'huruf') {

            foreach (
                range(
                    strtoupper($from),
                    strtoupper($to),
                ) as $letter
            ) {

                $classes[] = "{$tingkat}{$letter}";
            }

            return $classes;
        }

        foreach (
            range(
                (int) $from,
                (int) $to,
            ) as $number
        ) {

            $classes[] = "{$tingkat}.{$number}";
        }

        return $classes;
    }
}
