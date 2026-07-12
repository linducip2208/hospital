<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom CMS yang lebih kaya
        Schema::table('page_contents', function (Blueprint $table) {
            if (! Schema::hasColumn('page_contents', 'meta')) {
                $table->json('meta')->nullable()->after('content');
            }
            if (! Schema::hasColumn('page_contents', 'button_text')) {
                $table->string('button_text')->nullable()->after('image_url');
            }
            if (! Schema::hasColumn('page_contents', 'button_url')) {
                $table->string('button_url')->nullable()->after('button_text');
            }
            if (! Schema::hasColumn('page_contents', 'video_url')) {
                $table->string('video_url')->nullable()->after('button_url');
            }
        });

        // Pastikan section unik (1 record per section)
        try {
            Schema::table('page_contents', function (Blueprint $table) {
                $table->unique('section');
            });
        } catch (\Throwable $e) {
            // index sudah ada — abaikan
        }

        // Tambah role 'developer' untuk akses CMS
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
                'admin','doctor','staff','nurse','midwife','pharmacist','cashier','lab_technician','IT','finance','HR','director','developer'
            ) DEFAULT 'staff'");
        }
    }

    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            try { $table->dropUnique(['section']); } catch (\Throwable $e) {}
            $table->dropColumn(['meta', 'button_text', 'button_url', 'video_url']);
        });

        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
                'admin','doctor','staff','nurse','midwife','pharmacist','cashier','lab_technician','IT','finance','HR','director'
            ) DEFAULT 'staff'");
        }
    }
};
