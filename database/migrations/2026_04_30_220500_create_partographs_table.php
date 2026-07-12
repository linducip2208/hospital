<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partographs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maternity_id')->constrained('maternities')->cascadeOnDelete();
            $table->dateTime('recorded_at');
            $table->decimal('cervical_dilation', 3, 1)->nullable();
            $table->string('fetal_head_descent')->nullable();
            $table->integer('contractions_per_10min')->nullable();
            $table->integer('contraction_duration')->nullable();
            $table->integer('maternal_pulse')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('urine_output')->nullable();
            $table->enum('amniotic_fluid', ['intact', 'ruptured_clear', 'ruptured_meconium', 'ruptured_blood'])->nullable();
            $table->integer('oxytocin_drops')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['maternity_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partographs');
    }
};
