<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) { if (! Schema::hasColumn('purchase_orders', 'received_by')) $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete(); });
        Schema::table('purchase_order_items', function (Blueprint $table) { if (! Schema::hasColumn('purchase_order_items', 'drug_id')) $table->foreignId('drug_id')->nullable()->constrained('drugs')->nullOnDelete(); if (! Schema::hasColumn('purchase_order_items', 'received_quantity')) $table->unsignedInteger('received_quantity')->default(0); });
        if (DB::connection()->getDriverName() !== 'sqlite') DB::statement("ALTER TABLE purchase_orders MODIFY COLUMN status ENUM('draft','submitted','approved','ordered','partially_received','received','closed','cancelled') DEFAULT 'draft'");
    }
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) { if (Schema::hasColumn('purchase_order_items', 'drug_id')) $table->dropConstrainedForeignId('drug_id'); if (Schema::hasColumn('purchase_order_items', 'received_quantity')) $table->dropColumn('received_quantity'); });
        Schema::table('purchase_orders', function (Blueprint $table) { if (Schema::hasColumn('purchase_orders', 'received_by')) $table->dropConstrainedForeignId('received_by'); });
    }
};
