<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maternities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->dateTime('admission_date');
            $table->dateTime('delivery_date')->nullable();
            $table->enum('delivery_type', ['normal', 'caesar', 'vacuum', 'forceps'])->nullable();
            $table->enum('baby_gender', ['male', 'female'])->nullable();
            $table->decimal('baby_weight', 5, 2)->nullable();
            $table->decimal('baby_length', 4, 1)->nullable();
            $table->string('baby_name')->nullable();
            $table->text('complications')->nullable();
            $table->enum('status', ['admitted', 'in_labor', 'delivered', 'postpartum', 'discharged'])->default('admitted');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('patient_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maternities');
    }
};
