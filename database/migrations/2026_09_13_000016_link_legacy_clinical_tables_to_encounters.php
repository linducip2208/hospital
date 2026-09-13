<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['medical_records', 'payments', 'prescriptions', 'lab_tests', 'radiologies', 'vital_signs_records', 'nursing_cares', 'referrals'];

        foreach ($tables as $tableName) {
            if (! Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'encounter_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['medical_records', 'payments', 'prescriptions', 'lab_tests', 'radiologies', 'vital_signs_records', 'nursing_cares', 'referrals'] as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'encounter_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['encounter_id']);
                $table->dropColumn('encounter_id');
            });
        }
    }
};
