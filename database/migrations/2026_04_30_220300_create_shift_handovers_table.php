<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_nurse_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_nurse_id')->constrained('users')->cascadeOnDelete();
            $table->enum('shift_from', ['pagi', 'siang', 'malam']);
            $table->enum('shift_to', ['pagi', 'siang', 'malam']);
            $table->date('handover_date');
            $table->text('patient_summary')->nullable();
            $table->text('important_notes')->nullable();
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->timestamps();

            $table->index(['handover_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_handovers');
    }
};
