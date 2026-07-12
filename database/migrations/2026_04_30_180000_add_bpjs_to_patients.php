<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('bpjs_number', 20)->nullable()->after('nik');
            $table->boolean('nik_verified')->default(false)->after('bpjs_number');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['bpjs_number', 'nik_verified']);
        });
    }
};
