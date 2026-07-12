<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anc_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('midwife_id')->constrained('users')->cascadeOnDelete();
            $table->integer('visit_number');
            $table->date('visit_date');
            $table->integer('gestational_age')->nullable();
            $table->decimal('weight', 5, 1)->nullable();
            $table->string('blood_pressure')->nullable();
            $table->decimal('fundal_height', 5, 1)->nullable();
            $table->integer('fetal_heart_rate')->nullable();
            $table->string('fetal_position')->nullable();
            $table->decimal('hemoglobin', 4, 1)->nullable();
            $table->string('urine_protein')->nullable();
            $table->string('tetanus_immunization')->nullable();
            $table->integer('fe_tablets')->nullable();
            $table->text('complaints')->nullable();
            $table->integer('risk_score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anc_records');
    }
};
