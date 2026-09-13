<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'encounter_id')) {
                $table->foreignId('encounter_id')->nullable()->after('medical_record_id')
                    ->constrained('encounters')->nullOnDelete();
            }
        });

        Schema::table('journal_entries', function (Blueprint $table) {
            if (! Schema::hasColumn('journal_entries', 'source_type')) {
                $table->string('source_type')->nullable()->after('reference');
                $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
                $table->index(['source_type', 'source_id'], 'journal_source_idx');
                $table->unique(['source_type', 'source_id'], 'journal_source_unique');
            }
        });

        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash','transfer','debit','credit','qris','card','insurance','bpjs','corporate','other') DEFAULT 'cash'");
        }
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropUnique('journal_source_unique');
            $table->dropIndex('journal_source_idx');
            $table->dropColumn(['source_type', 'source_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('encounter_id');
        });
    }
};
