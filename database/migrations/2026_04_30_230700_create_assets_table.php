<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('name');
            $table->enum('category', ['medical', 'non_medical', 'IT', 'furniture', 'vehicle', 'building', 'other'])->default('medical');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'broken'])->default('good');
            $table->enum('status', ['active', 'maintenance', 'disposed'])->default('active');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
