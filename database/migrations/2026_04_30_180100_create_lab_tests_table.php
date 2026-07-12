<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->string('test_name');
            $table->string('test_type')->nullable();
            $table->string('sample_type')->nullable();
            $table->enum('status', ['requested', 'sample_collected', 'in_progress', 'completed', 'cancelled'])->default('requested');
            $table->text('results')->nullable();
            $table->timestamp('result_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('patient_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};
