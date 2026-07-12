<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Link clinical_pathways ke medical_records (apply protokol klinis ke rekam medis).
 * Nullable — dokter boleh tidak apply CP.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $t) {
            if (! Schema::hasColumn('medical_records', 'clinical_pathway_id')) {
                $t->foreignId('clinical_pathway_id')->nullable()->after('appointment_id')
                    ->constrained('clinical_pathways')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $t) {
            if (Schema::hasColumn('medical_records', 'clinical_pathway_id')) {
                try { $t->dropForeign(['clinical_pathway_id']); } catch (\Throwable $e) {}
                $t->dropColumn('clinical_pathway_id');
            }
        });
    }
};
