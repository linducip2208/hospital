<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Normalisasi defensif status payments warisan.
 * Migrasi lama 2026_04_30_071057 drop kolom status (MySQL enum menolak
 * nilai di luar daftar, sqlite tidak) — migrasi ini memetakan sisa nilai
 * lama ke status valid secara idempoten. Tidak menghapus data.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('payments')->where('status', 'paid')->update(['status' => 'completed']);
        DB::table('payments')->where('status', 'partial')->update(['status' => 'pending']);
    }

    public function down(): void
    {
        // Tidak ada rollback data — pemetaan satu arah yang aman.
    }
};
