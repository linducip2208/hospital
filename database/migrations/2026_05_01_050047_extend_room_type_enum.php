<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Skip untuk SQLite (test environment) — SQLite tidak support ENUM, kolom string sudah cukup
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement("ALTER TABLE `rooms` CHANGE `room_type` `room_type` ENUM('VIP','Kelas 1','Kelas 2','Kelas 3','ICU','NICU','OK') NOT NULL");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement("ALTER TABLE `rooms` CHANGE `room_type` `room_type` ENUM('VIP','Kelas 1','Kelas 2','Kelas 3') NOT NULL");
    }
};
