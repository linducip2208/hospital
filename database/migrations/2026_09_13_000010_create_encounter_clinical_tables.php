<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->string('encounter_no')->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('polyclinic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('hospital_bed_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('encounter_type', ['outpatient', 'inpatient', 'emergency', 'telemedicine', 'surgery']);
            $table->enum('payer_type', ['general', 'bpjs', 'insurance', 'corporate'])->default('general');
            $table->enum('status', ['registered', 'waiting', 'in_progress', 'observation', 'admitted', 'discharged', 'completed', 'cancelled'])->default('registered')->index();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['patient_id', 'status']);
            $table->index(['appointment_id', 'status']);
        });

        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->restrictOnDelete();
            $table->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('icd10_code', 20)->nullable()->index();
            $table->string('diagnosis_name');
            $table->enum('diagnosis_type', ['primary', 'secondary', 'differential'])->default('primary');
            $table->boolean('is_confirmed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['encounter_id', 'diagnosis_type']);
        });

        Schema::create('clinical_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('patient_id')->constrained()->restrictOnDelete();
            $table->foreignId('encounter_id')->constrained()->restrictOnDelete();
            $table->foreignId('ordering_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('destination_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('order_type', ['laboratory', 'radiology', 'procedure', 'pharmacy', 'diet', 'blood', 'consultation']);
            $table->enum('priority', ['routine', 'urgent', 'cito'])->default('routine');
            $table->enum('status', ['ordered', 'accepted', 'in_progress', 'resulted', 'verified', 'completed', 'cancelled'])->default('ordered')->index();
            $table->dateTime('ordered_at');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['encounter_id', 'order_type', 'status']);
        });

        Schema::create('clinical_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinical_order_id')->constrained()->cascadeOnDelete();
            $table->string('item_type')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('description');
            $table->unsignedInteger('quantity')->default(1);
            $table->text('result_text')->nullable();
            $table->json('result_data')->nullable();
            $table->string('reference_range')->nullable();
            $table->string('unit')->nullable();
            $table->enum('abnormal_flag', ['normal', 'low', 'high', 'critical', 'unknown'])->default('unknown');
            $table->boolean('critical_flag')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
            $table->index(['item_type', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_order_items');
        Schema::dropIfExists('clinical_orders');
        Schema::dropIfExists('diagnoses');
        Schema::dropIfExists('encounters');
    }
};
