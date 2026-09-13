<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['lab_tests', 'radiologies'] as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (! Schema::hasColumn($tableName, 'clinical_order_id')) {
                        $table->foreignId('clinical_order_id')->nullable()->constrained('clinical_orders')->nullOnDelete();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['lab_tests', 'radiologies'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'clinical_order_id')) {
                Schema::table($tableName, fn (Blueprint $table) => $table->dropConstrainedForeignId('clinical_order_id'));
            }
        }
    }
};
