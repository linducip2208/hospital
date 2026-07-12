<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->dateTime('donation_date');
            $table->dateTime('expiry_date');
            $table->integer('quantity_ml')->nullable();
            $table->enum('status', ['available', 'used', 'expired', 'discarded'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('blood_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_donations');
    }
};
