<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_administrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('drug_id')->nullable()->constrained('drugs')->nullOnDelete();
            $table->string('drug_name');
            $table->string('dosage')->nullable();
            $table->enum('route', ['oral', 'IV', 'IM', 'SC', 'topical', 'inhalation', 'other'])->default('oral');
            $table->dateTime('administered_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'administered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_administrations');
    }
};
