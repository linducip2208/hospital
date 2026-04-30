<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix payments status: add 'completed' and remove 'paid'/'partial'
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])
                ->default('pending')
                ->after('payment_method');
        });

        // Add soft deletes to medical_records
        Schema::table('medical_records', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('status', ['pending', 'paid', 'partial', 'refunded', 'cancelled'])
                ->default('pending')
                ->after('payment_method');
        });

        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
