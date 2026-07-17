<?php

namespace App\Filament\Resources\ProposalKegiatans\Pages;

use App\Filament\Resources\ProposalKegiatans\ProposalKegiatanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Ekstrakurikuler;
use App\Support\ProposalTemplate;

class CreateProposalKegiatan extends CreateRecord
{
    protected static string $resource = ProposalKegiatanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by_id'] = Auth::id();

        $ekstrakurikuler = Ekstrakurikuler::findOrFail(
            $data['ekstrakurikuler_id']
        );

        $template = ProposalTemplate::make(
            $ekstrakurikuler,
            $data['nama_kegiatan'],
            $data['nama_penyelenggara'],
            ucfirst($data['tingkat_kegiatan'])
        );

        if (blank(strip_tags($data['latar_belakang'] ?? ''))) {
            $data['latar_belakang'] = $template['latar_belakang'];
        }

        if (blank(strip_tags($data['dasar_kegiatan'] ?? ''))) {
            $data['dasar_kegiatan'] = $template['dasar_kegiatan'];
        }

        if (blank(strip_tags($data['tujuan'] ?? ''))) {
            $data['tujuan'] = $template['tujuan'];
        }

        if (blank(strip_tags($data['penutup'] ?? ''))) {
            $data['penutup'] = $template['penutup'];
        }

        return $data;
    }
}
