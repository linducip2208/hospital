<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambulance_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ambulance_id')->constrained()->cascadeOnDelete();
            $table->string('patient_name');
            $table->string('pickup_location');
            $table->string('destination')->nullable();
            $table->dateTime('call_date');
            $table->enum('status', ['pending', 'dispatched', 'en_route', 'arrived', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_calls');
    }
};
