<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            if (! Schema::hasColumn('medical_records', 'status')) $table->enum('status', ['draft', 'finalized'])->default('draft')->index();
            if (! Schema::hasColumn('medical_records', 'finalized_by')) $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            if (! Schema::hasColumn('medical_records', 'finalized_at')) $table->dateTime('finalized_at')->nullable();
        });

        Schema::table('lab_tests', function (Blueprint $table) {
            if (! Schema::hasColumn('lab_tests', 'accession_no')) $table->string('accession_no')->nullable()->unique();
            if (! Schema::hasColumn('lab_tests', 'specimen')) $table->string('specimen')->nullable();
            if (! Schema::hasColumn('lab_tests', 'collected_at')) $table->dateTime('collected_at')->nullable();
            if (! Schema::hasColumn('lab_tests', 'collected_by')) $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            if (! Schema::hasColumn('lab_tests', 'verified_by')) $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            if (! Schema::hasColumn('lab_tests', 'verified_at')) $table->dateTime('verified_at')->nullable();
            if (! Schema::hasColumn('lab_tests', 'reference_range')) $table->string('reference_range')->nullable();
            if (! Schema::hasColumn('lab_tests', 'unit')) $table->string('unit')->nullable();
            if (! Schema::hasColumn('lab_tests', 'abnormal_flag')) $table->string('abnormal_flag')->nullable();
            if (! Schema::hasColumn('lab_tests', 'critical_flag')) $table->boolean('critical_flag')->default(false);
            if (! Schema::hasColumn('lab_tests', 'result_status')) $table->string('result_status')->nullable();
        });

        Schema::table('radiologies', function (Blueprint $table) {
            if (! Schema::hasColumn('radiologies', 'modality')) $table->string('modality')->nullable();
            if (! Schema::hasColumn('radiologies', 'radiologist_id')) $table->foreignId('radiologist_id')->nullable()->constrained('doctors')->nullOnDelete();
            if (! Schema::hasColumn('radiologies', 'verified_by')) $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            if (! Schema::hasColumn('radiologies', 'verified_at')) $table->dateTime('verified_at')->nullable();
            if (! Schema::hasColumn('radiologies', 'result_status')) $table->string('result_status')->nullable();
            if (! Schema::hasColumn('radiologies', 'pacs_reference_url')) $table->text('pacs_reference_url')->nullable();
        });

        foreach (['patient_screenings', 'discharge_summaries', 'surgeries', 'emergencies', 'medication_administrations', 'nurse_assignments'] as $tableName) {
            if (! Schema::hasTable($tableName)) continue;
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'encounter_id')) {
                    $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                }
            });
        }
        if (Schema::hasTable('discharge_summaries')) {
            Schema::table('discharge_summaries', function (Blueprint $table) {
                if (! Schema::hasColumn('discharge_summaries', 'status')) $table->enum('status', ['draft', 'finalized'])->default('draft')->index();
                if (! Schema::hasColumn('discharge_summaries', 'finalized_by')) $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
                if (! Schema::hasColumn('discharge_summaries', 'finalized_at')) $table->dateTime('finalized_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['patient_screenings', 'discharge_summaries', 'surgeries', 'emergencies', 'medication_administrations', 'nurse_assignments'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'encounter_id')) {
                Schema::table($tableName, function (Blueprint $table) { $table->dropConstrainedForeignId('encounter_id'); });
            }
        }
        if (Schema::hasTable('discharge_summaries')) {
            Schema::table('discharge_summaries', function (Blueprint $table) {
                foreach (['finalized_by', 'finalized_at', 'status'] as $column) if (Schema::hasColumn('discharge_summaries', $column)) { if ($column === 'finalized_by') $table->dropConstrainedForeignId($column); else $table->dropColumn($column); }
            });
        }
        Schema::table('radiologies', function (Blueprint $table) { foreach (['radiologist_id', 'verified_by'] as $column) if (Schema::hasColumn('radiologies', $column)) $table->dropConstrainedForeignId($column); foreach (['modality', 'verified_at', 'result_status', 'pacs_reference_url'] as $column) if (Schema::hasColumn('radiologies', $column)) $table->dropColumn($column); });
        Schema::table('lab_tests', function (Blueprint $table) { foreach (['collected_by', 'verified_by'] as $column) if (Schema::hasColumn('lab_tests', $column)) $table->dropConstrainedForeignId($column); foreach (['accession_no', 'specimen', 'collected_at', 'verified_at', 'reference_range', 'unit', 'abnormal_flag', 'critical_flag', 'result_status'] as $column) if (Schema::hasColumn('lab_tests', $column)) $table->dropColumn($column); });
        Schema::table('medical_records', function (Blueprint $table) { if (Schema::hasColumn('medical_records', 'finalized_by')) $table->dropConstrainedForeignId('finalized_by'); foreach (['finalized_at', 'status'] as $column) if (Schema::hasColumn('medical_records', $column)) $table->dropColumn($column); });
    }
};
