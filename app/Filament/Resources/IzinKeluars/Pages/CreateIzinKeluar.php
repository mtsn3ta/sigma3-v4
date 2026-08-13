<?php

namespace App\Filament\Resources\IzinKeluars\Pages;

use App\Filament\Resources\IzinKeluars\IzinKeluarResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CreateIzinKeluar extends CreateRecord
{
    protected static string $resource = IzinKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pegawai_id'] = Auth::user()->pegawai->id;
        $data['status_proses'] ??= 'keluar';
        $data['jam_keluar'] ??= now('Asia/Jakarta')->format('H:i:s');

        return $data;
    }

    // PERBAIKAN UTAMA: Mencegat proses penyimpanan (Bypass Insert)
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Ambil array siswa_id (karena form sekarang multiple)
        $siswaIds = $data['siswa_id'];

        // 2. Hapus siswa_id dari $data master agar tidak error saat di-insert
        unset($data['siswa_id']);

        $models = [];

        // 3. Looping: Buat data izin keluar sebanyak jumlah siswa yang dipilih
        foreach ($siswaIds as $siswaId) {
            $recordData = $data;
            $recordData['siswa_id'] = $siswaId;

            // Simpan ke database satu per satu
            $models[] = static::getModel()::create($recordData);
        }

        // 4. Filament mewajibkan kita mengembalikan 1 model untuk diarahkan (redirect) ke halaman List.
        // Kita kembalikan siswa pertama dari antrean sebagai perwakilan.
        return $models[0];
    }

    public function getTitle(): string
    {
        return 'Tambah Izin Keluar';
    }
}
