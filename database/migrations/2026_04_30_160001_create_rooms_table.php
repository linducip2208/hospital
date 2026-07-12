<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('room_type', ['VIP', 'Kelas 1', 'Kelas 2', 'Kelas 3']);
            $table->integer('floor')->nullable();
            $table->integer('bed_count')->default(1);
            $table->decimal('price_per_day', 12, 2)->default(0);
            $table->json('facilities')->nullable()->comment('Fasilitas: AC, TV, Kamar Mandi');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
