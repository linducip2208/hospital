<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('surgeries')) return;
        Schema::table('surgeries', function (Blueprint $table) {
            if (! Schema::hasColumn('surgeries', 'encounter_id')) $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            if (! Schema::hasColumn('surgeries', 'surgeon_id')) $table->foreignId('surgeon_id')->nullable()->constrained('doctors')->nullOnDelete();
            if (! Schema::hasColumn('surgeries', 'assistant_id')) $table->foreignId('assistant_id')->nullable()->constrained('doctors')->nullOnDelete();
            if (! Schema::hasColumn('surgeries', 'anesthetist_id')) $table->foreignId('anesthetist_id')->nullable()->constrained('doctors')->nullOnDelete();
            if (! Schema::hasColumn('surgeries', 'operating_room')) $table->string('operating_room')->nullable();
            if (! Schema::hasColumn('surgeries', 'anesthesia')) $table->string('anesthesia')->nullable();
            if (! Schema::hasColumn('surgeries', 'implants_materials')) $table->text('implants_materials')->nullable();
            if (! Schema::hasColumn('surgeries', 'started_at')) $table->dateTime('started_at')->nullable();
            if (! Schema::hasColumn('surgeries', 'ended_at')) $table->dateTime('ended_at')->nullable();
            if (! Schema::hasColumn('surgeries', 'outcome')) $table->string('outcome')->nullable();
            if (! Schema::hasColumn('surgeries', 'complications')) $table->text('complications')->nullable();
            if (! Schema::hasColumn('surgeries', 'charge_amount')) $table->decimal('charge_amount', 14, 2)->default(0);
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('surgeries')) return;
        Schema::table('surgeries', function (Blueprint $table) {
            foreach (['anesthetist_id', 'assistant_id', 'surgeon_id', 'encounter_id'] as $column) if (Schema::hasColumn('surgeries', $column)) $table->dropConstrainedForeignId($column);
            foreach (['operating_room', 'anesthesia', 'implants_materials', 'started_at', 'ended_at', 'outcome', 'complications', 'charge_amount'] as $column) if (Schema::hasColumn('surgeries', $column)) $table->dropColumn($column);
        });
    }
};
