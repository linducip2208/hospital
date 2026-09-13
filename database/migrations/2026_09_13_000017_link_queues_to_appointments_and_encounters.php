<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('queues', 'appointment_id')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            });
        }
        if (! Schema::hasColumn('queues', 'encounter_id')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->foreignId('encounter_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            if (Schema::hasColumn('queues', 'encounter_id')) {
                $table->dropForeign(['encounter_id']);
                $table->dropColumn('encounter_id');
            }
            if (Schema::hasColumn('queues', 'appointment_id')) {
                $table->dropForeign(['appointment_id']);
                $table->dropColumn('appointment_id');
            }
        });
    }
};
