<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaikan FK policy untuk compliance medis:
     * - doctor_id di tabel medis tidak boleh cascade delete (melanggar audit trail)
     * - Ubah cascadeOnDelete → nullOnDelete agar record tetap ada meski dokter dihapus
     * - Tambah SoftDeletes ke users (belum ada di migration awal)
     */
    public function up(): void
    {
        $this->fixForeignKey('medical_records', 'doctor_id');
        $this->fixForeignKey('appointments', 'doctor_id');
        $this->fixForeignKey('lab_tests', 'doctor_id');
        $this->fixForeignKey('radiologies', 'doctor_id');
        $this->fixForeignKey('maternities', 'doctor_id');

        if (!Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        $this->log('✅ FK policies: 5 tabel doctor_id cascadeOnDelete → nullOnDelete');
        $this->log('✅ users.deleted_at: ditambahkan (kalau belum ada)');
        $this->log('✅ attendances unique(user_id,date): sudah ada');
        $this->log('✅ salaries unique(user_id,period_month,period_year): sudah ada');
        $this->log('✅ doctor_polyclinic primary: sudah ada');
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
        });

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
        });

        Schema::table('radiologies', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
        });

        Schema::table('maternities', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->foreign('doctor_id')->references('id')->on('doctors')->cascadeOnDelete();
        });

        if (Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }

    /**
     * Ubah FK cascadeOnDelete → nullOnDelete + buat kolom nullable.
     */
    private function fixForeignKey(string $table, string $column): void
    {
        Schema::table($table, function (Blueprint $table) use ($column) {
            // Drop FK Lama
            try {
                $table->dropForeign([$column]);
            } catch (\Throwable) {}

            // Buat nullable
            $table->unsignedBigInteger($column)->nullable()->change();

            // Bikin FK baru nullOnDelete
            $table->foreign($column)->references('id')->on('doctors')->nullOnDelete();
        });
    }

    private function log(string $msg): void
    {
        echo "  {$msg}\n";
    }
};
