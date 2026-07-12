<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_cares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nurse_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('assessment_date');
            $table->text('subjective_data')->nullable();
            $table->text('objective_data')->nullable();
            $table->text('nursing_diagnosis')->nullable();
            $table->text('nursing_plan')->nullable();
            $table->text('nursing_action')->nullable();
            $table->text('evaluation')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_cares');
    }
};
