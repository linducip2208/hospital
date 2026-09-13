<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('emergencies')) {
            Schema::table('emergencies', function (Blueprint $table) {
                if (! Schema::hasColumn('emergencies', 'encounter_id')) {
                    $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                }
                if (! Schema::hasColumn('emergencies', 'arrived_at')) $table->dateTime('arrived_at')->nullable()->index();
                if (! Schema::hasColumn('emergencies', 'triaged_at')) $table->dateTime('triaged_at')->nullable();
                if (! Schema::hasColumn('emergencies', 'treatment_started_at')) $table->dateTime('treatment_started_at')->nullable();
                if (! Schema::hasColumn('emergencies', 'disposition_at')) $table->dateTime('disposition_at')->nullable();
            });
        }

        if (Schema::hasTable('medication_administrations')) {
            Schema::table('medication_administrations', function (Blueprint $table) {
                if (! Schema::hasColumn('medication_administrations', 'encounter_id')) {
                    $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                }
                if (! Schema::hasColumn('medication_administrations', 'prescription_id')) {
                    $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
                }
                if (! Schema::hasColumn('medication_administrations', 'prescription_item_id')) {
                    $table->foreignId('prescription_item_id')->nullable()->constrained('prescription_items')->nullOnDelete();
                }
                $table->index(['encounter_id', 'administered_at']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('medication_administrations')) {
            Schema::table('medication_administrations', function (Blueprint $table) {
                foreach (['prescription_item_id', 'prescription_id', 'encounter_id'] as $column) {
                    if (Schema::hasColumn('medication_administrations', $column)) $table->dropConstrainedForeignId($column);
                }
            });
        }
        if (Schema::hasTable('emergencies')) {
            Schema::table('emergencies', function (Blueprint $table) {
                if (Schema::hasColumn('emergencies', 'encounter_id')) $table->dropConstrainedForeignId('encounter_id');
                foreach (['arrived_at', 'triaged_at', 'treatment_started_at', 'disposition_at'] as $column) {
                    if (Schema::hasColumn('emergencies', $column)) $table->dropColumn($column);
                }
            });
        }
    }
};
