<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baby_immunizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('maternity_id')->nullable()->constrained('maternities')->nullOnDelete();
            $table->string('vaccine_name');
            $table->date('scheduled_date');
            $table->date('given_date')->nullable();
            $table->integer('dose_number')->default(1);
            $table->enum('status', ['scheduled', 'given', 'missed'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baby_immunizations');
    }
};
