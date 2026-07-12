<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('triage', ['red', 'yellow', 'green', 'black'])->default('green');
            $table->string('arrival_mode')->nullable();
            $table->text('complaint');
            $table->text('diagnosis')->nullable();
            $table->text('action_taken')->nullable();
            $table->enum('status', ['waiting', 'in_treatment', 'observation', 'discharged', 'referred', 'deceased'])->default('waiting');
            $table->dateTime('discharge_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('triage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergencies');
    }
};
