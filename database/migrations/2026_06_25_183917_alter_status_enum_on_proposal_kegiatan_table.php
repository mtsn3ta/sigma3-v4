<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE proposal_kegiatan
            MODIFY status ENUM(
                'draft',
                'pending_pembina',
                'pending_waka',
                'pending_kepala',
                'revision',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE proposal_kegiatan
            MODIFY status ENUM(
                'draft',
                'pending_pembina',
                'pending_waka',
                'pending_kepala',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'draft'
        ");
    }
};
