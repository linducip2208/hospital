<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ----------------------------------------------------------------
        // 1. Rename columns on medical_records
        // ----------------------------------------------------------------
        Schema::table('medical_records', function (Blueprint $table) {
            $table->renameColumn('treatment_notes', 'action');
        });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->renameColumn('prescription', 'medicine');
        });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->renameColumn('follow_up', 'notes');
        });

        // ----------------------------------------------------------------
        // 2. Rename column on payments: total → amount
        // ----------------------------------------------------------------
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('total', 'amount');
        });

        // ----------------------------------------------------------------
        // 3. Fix payment_method enum: add debit, credit, qris, remove other
        // ----------------------------------------------------------------
        // SQLite (used in testing) does not support MODIFY COLUMN or ENUM
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
                'cash','transfer','debit','credit','qris','card','insurance','other'
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cash'");
        }

        // ----------------------------------------------------------------
        // 4. Add missing indexes
        // ----------------------------------------------------------------
        Schema::table('medical_records', function (Blueprint $table) {
            $table->index('appointment_id', 'idx_medical_records_appointment_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('patient_id', 'idx_payments_patient_id');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->index('appointment_id', 'idx_payments_appointment_id');
        });
    }

    public function down(): void
    {
        // Remove indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_appointment_id');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_patient_id');
        });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropIndex('idx_medical_records_appointment_id');
        });

        // Restore payment_method enum to original (MySQL only)
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM(
                'cash','transfer','card','insurance','other'
            ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'cash'");
        }

        // Rename back
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('amount', 'total');
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->renameColumn('notes', 'follow_up');
        });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->renameColumn('medicine', 'prescription');
        });
        Schema::table('medical_records', function (Blueprint $table) {
            $table->renameColumn('action', 'treatment_notes');
        });
    }
};
