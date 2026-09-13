<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drug_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_id')->constrained()->restrictOnDelete();
            $table->string('batch_no');
            $table->string('lot_no')->nullable();
            $table->date('expiry_date')->index();
            $table->unsignedInteger('quantity_received')->default(0);
            $table->unsignedInteger('quantity_available')->default(0);
            $table->decimal('purchase_price', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2)->default(0);
            $table->timestamps();
            $table->unique(['drug_id', 'batch_no']);
        });

        Schema::create('dispensings', function (Blueprint $table) {
            $table->id();
            $table->string('dispensing_no')->unique();
            $table->foreignId('prescription_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pharmacist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'verified', 'dispensed', 'cancelled'])->default('draft')->index();
            $table->dateTime('dispensed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('dispensing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispensing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prescription_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('drug_id')->constrained()->restrictOnDelete();
            $table->foreignId('drug_batch_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_id')->constrained()->restrictOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('movement_type', ['purchase', 'dispense', 'return', 'adjustment', 'transfer', 'destruction']);
            $table->integer('quantity');
            $table->unsignedInteger('stock_before')->default(0);
            $table->unsignedInteger('stock_after')->default(0);
            $table->nullableMorphs('reference');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['drug_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('dispensing_items');
        Schema::dropIfExists('dispensings');
        Schema::dropIfExists('drug_batches');
    }
};
