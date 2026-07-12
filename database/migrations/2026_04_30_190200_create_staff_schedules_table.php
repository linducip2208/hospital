<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('shift_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('department')->nullable();
            $table->enum('shift_type', ['morning', 'afternoon', 'night', 'on_call', 'off'])->default('morning');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('shift_date');
            $table->index('department');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_schedules');
    }
};
