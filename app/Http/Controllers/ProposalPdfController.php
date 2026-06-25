<?php

namespace App\Http\Controllers;

use App\Models\ProposalKegiatan;
use App\Services\ProposalPdfService;

class ProposalPdfController
{
    public function __invoke(
        ProposalKegiatan $proposal
    ) {
        return ProposalPdfService::download($proposal);
    }
}
