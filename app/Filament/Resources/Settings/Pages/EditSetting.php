<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use App\Models\Setting;
use Filament\Resources\Pages\EditRecord;
use App\Services\ActivityLogService;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    public function mount(int|string $record = null): void
    {
        // 1. Cek apakah sudah ada data setting di database
        $setting = Setting::first();

        // 2. Jika belum ada, buat 1 data default (mencegah error 404)
        if (! $setting) {
            $setting = Setting::create([
                'nama_madrasah' => 'Madrasah Default',
                // Isi default lain jika ada kolom yang wajib (required) di DB
            ]);
        }

        // 3. Masukkan ID setting tersebut ke parent mount
        parent::mount($setting->id);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    // Ubah label tombol save jika diperlukan
    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()->label('Simpan Pengaturan');
    }
    protected function afterSave(): void
    {
        ActivityLogService::log(
            'Update Setting',
            'Mengubah pengaturan sistem.'
        );
    }
    public function getTitle(): string
    {
        return 'Pengaturan Umum & Jam Absen';
    }
}
