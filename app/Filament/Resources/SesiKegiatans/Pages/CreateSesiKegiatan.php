<?php

namespace App\Filament\Resources\SesiKegiatans\Pages;

use App\Models\AnggotaEkskul;
use App\Models\KehadiranEkskul;
use App\Filament\Resources\SesiKegiatans\SesiKegiatanResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ActivityLogService;

class CreateSesiKegiatan extends CreateRecord
{
    protected static string $resource = SesiKegiatanResource::class;

    protected function afterCreate(): void
    {
        $sesi = $this->record;

        $anggota = AnggotaEkskul::query()
            ->where('ekstrakurikuler_id', $sesi->ekstrakurikuler_id)
            ->where('status', 'aktif')
            ->get();

        foreach ($anggota as $item) {

            KehadiranEkskul::firstOrCreate(
                [
                    'sesi_kegiatan_id' => $sesi->id,
                    'siswa_id' => $item->siswa_id,
                ],
                [
                    'status' => 'tidak_hadir',   // ← diganti dari 'alpa'
                    'sumber' => 'manual',
                ]
            );
        }

        ActivityLogService::log(
            'Create Sesi',
            $this->record->ekstrakurikuler->nama
        );
    }
    public function getTitle(): string
    {
        return 'Tambah Sesi Kegiatan';
    }
}
