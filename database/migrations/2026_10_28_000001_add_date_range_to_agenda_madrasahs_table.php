<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agenda_madrasahs', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('id');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
        });

        // 1. Salin data tanggal yang sudah ada ke tanggal_mulai dan tanggal_selesai
        DB::table('agenda_madrasahs')
            ->whereNull('tanggal_mulai')
            ->update([
                'tanggal_mulai' => DB::raw('tanggal'),
                'tanggal_selesai' => DB::raw('tanggal'),
            ]);

        // 2. Gabungkan data duplikat harian yang sama (nama_agenda, tipe_agenda, kelas_id)
        $all = DB::table('agenda_madrasahs')->orderBy('tanggal', 'asc')->get();
        $grouped = $all->groupBy(function ($item) {
            return trim($item->nama_agenda) . '|' . $item->tipe_agenda . '|' . ($item->kelas_id ?? 'null');
        });

        foreach ($grouped as $items) {
            if ($items->count() > 1) {
                $first = $items->first();
                $last = $items->last();

                // Update baris pertama dengan rentang tanggal penuh
                DB::table('agenda_madrasahs')
                    ->where('id', $first->id)
                    ->update([
                        'tanggal_mulai' => $first->tanggal,
                        'tanggal_selesai' => $last->tanggal,
                        'tanggal' => $first->tanggal,
                    ]);

                // Hapus baris duplikat lainnya
                $redundantIds = $items->slice(1)->pluck('id')->all();
                DB::table('agenda_madrasahs')->whereIn('id', $redundantIds)->delete();
            }
        }
    }

    public function down(): void
    {
        Schema::table('agenda_madrasahs', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai', 'tanggal_selesai']);
        });
    }
};
