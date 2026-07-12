<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postnatal_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('midwife_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('maternity_id')->nullable()->constrained('maternities')->nullOnDelete();
            $table->integer('visit_number');
            $table->date('visit_date');
            $table->decimal('fundal_height', 5, 1)->nullable();
            $table->enum('lochia', ['rubra', 'serosa', 'alba'])->nullable();
            $table->text('perineum_wound')->nullable();
            $table->enum('breastfeeding', ['exclusive', 'mixed', 'formula'])->nullable();
            $table->text('complaints')->nullable();
            $table->enum('contraception_started', ['none', 'IUD', 'implant', 'injection', 'pill', 'condom', 'sterilization'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postnatal_records');
    }
};
