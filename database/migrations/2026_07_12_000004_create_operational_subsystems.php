<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vendor / Supplier
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category')->nullable(); // farmasi, alkes, umum
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Kalibrasi Alat Kesehatan (link ke assets)
        Schema::create('equipment_calibrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->date('calibration_date');
            $table->date('next_due_date');
            $table->string('performed_by')->nullable();      // teknisi / lembaga kalibrasi
            $table->string('certificate_no')->nullable();
            $table->enum('result', ['pass', 'pass_with_note', 'fail'])->default('pass');
            $table->enum('status', ['scheduled', 'completed', 'overdue'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('next_due_date');
        });

        // Limbah Medis B3
        Schema::create('medical_wastes', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_no')->unique();
            $table->enum('waste_type', ['infectious', 'sharps', 'pharmaceutical', 'chemical', 'radioactive', 'pathological', 'general']);
            $table->decimal('weight_kg', 8, 2);
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->date('collection_date');
            $table->date('disposal_date')->nullable();
            $table->string('transporter')->nullable();       // pihak pengangkut berizin
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->enum('status', ['stored', 'transported', 'disposed'])->default('stored');
            $table->string('handled_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'collection_date']);
        });

        // Vendor link + reorder threshold di drugs & purchase_orders
        Schema::table('drugs', function (Blueprint $table) {
            if (! Schema::hasColumn('drugs', 'reorder_level')) {
                $table->integer('reorder_level')->default(50)->after('stock');
            }
            if (! Schema::hasColumn('drugs', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('reorder_level')->constrained('vendors')->nullOnDelete();
            }
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_orders', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('department_id')->constrained('vendors')->nullOnDelete();
            }
            if (! Schema::hasColumn('purchase_orders', 'auto_generated')) {
                $table->boolean('auto_generated')->default(false)->after('vendor_id');
            }
        });

        // GPS tracking di ambulances
        Schema::table('ambulances', function (Blueprint $table) {
            if (! Schema::hasColumn('ambulances', 'last_lat')) {
                $table->decimal('last_lat', 10, 7)->nullable();
                $table->decimal('last_lng', 10, 7)->nullable();
                $table->timestamp('location_updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ambulances', function (Blueprint $table) {
            $table->dropColumn(['last_lat', 'last_lng', 'location_updated_at']);
        });
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
            $table->dropColumn('auto_generated');
        });
        Schema::table('drugs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vendor_id');
            $table->dropColumn('reorder_level');
        });
        Schema::dropIfExists('medical_wastes');
        Schema::dropIfExists('equipment_calibrations');
        Schema::dropIfExists('vendors');
    }
};
