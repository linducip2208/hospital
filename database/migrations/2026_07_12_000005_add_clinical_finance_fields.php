<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ICD-10 coding di rekam medis
        Schema::table('medical_records', function (Blueprint $table) {
            if (! Schema::hasColumn('medical_records', 'icd10_code')) {
                $table->string('icd10_code', 10)->nullable()->after('diagnosis');
                $table->string('icd10_name')->nullable()->after('icd10_code');
            }
        });

        // Cost center + kelas perawatan di payments (untuk billing breakdown & P&L per unit)
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('patient_id')->constrained('departments')->nullOnDelete();
            }
            if (! Schema::hasColumn('payments', 'payer_type')) {
                $table->enum('payer_type', ['umum', 'bpjs', 'asuransi'])->default('umum')->after('payment_method');
            }
            if (! Schema::hasColumn('payments', 'service_class')) {
                $table->string('service_class', 20)->nullable()->after('payer_type'); // VIP, 1, 2, 3
            }
        });

        // INA-CBG group di klaim
        Schema::table('insurance_claims', function (Blueprint $table) {
            if (! Schema::hasColumn('insurance_claims', 'inacbg_code')) {
                $table->string('inacbg_code', 20)->nullable()->after('diagnosis_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropColumn(['icd10_code', 'icd10_name']);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('department_id');
            $table->dropColumn(['payer_type', 'service_class']);
        });
        Schema::table('insurance_claims', function (Blueprint $table) {
            $table->dropColumn('inacbg_code');
        });
    }
};
