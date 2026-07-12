<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ambulances', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number');
            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->enum('status', ['available', 'on_duty', 'maintenance', 'out_of_service'])->default('available');
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulances');
    }
};
