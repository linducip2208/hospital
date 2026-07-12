<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_certificates', function (Blueprint $t) {
            $t->id();
            $t->string('cert_no')->unique();
            $t->enum('type', [
                'sick_leave', 'healthy', 'drug_free', 'pregnancy', 'not_pregnancy',
                'birth', 'death', 'visum', 'color_blind_free', 'medical_check_up',
            ])->index();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $t->date('issue_date');
            $t->date('rest_from')->nullable();
            $t->date('rest_until')->nullable();
            $t->unsignedSmallInteger('rest_days')->nullable();
            $t->string('diagnosis')->nullable();
            $t->string('purpose')->nullable();
            $t->json('exam_data')->nullable();
            $t->text('notes')->nullable();
            $t->enum('status', ['draft', 'issued', 'cancelled'])->default('issued')->index();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('informed_consents', function (Blueprint $t) {
            $t->id();
            $t->string('consent_no')->unique();
            $t->enum('kind', ['consent', 'refusal', 'aps'])->index();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->string('procedure_name');
            $t->text('procedure_description')->nullable();
            $t->text('risks')->nullable();
            $t->text('alternatives')->nullable();
            $t->string('signed_by_name')->nullable();
            $t->string('signed_by_relation')->nullable();
            $t->string('witness_name')->nullable();
            $t->dateTime('signed_at')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('prescriptions', function (Blueprint $t) {
            $t->id();
            $t->string('rx_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $t->date('prescribed_at');
            $t->boolean('is_iter')->default(false);
            $t->unsignedTinyInteger('iter_count')->default(0);
            $t->enum('status', ['draft', 'issued', 'dispensed', 'cancelled'])->default('issued')->index();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('prescription_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $t->foreignId('drug_id')->nullable()->constrained()->nullOnDelete();
            $t->string('drug_name');
            $t->string('dose')->nullable();
            $t->string('frequency')->nullable();
            $t->string('route')->nullable();
            $t->string('duration')->nullable();
            $t->unsignedInteger('quantity')->default(1);
            $t->string('unit')->nullable();
            $t->text('instructions')->nullable();
            $t->boolean('is_compounded')->default(false);
            $t->boolean('is_high_alert')->default(false);
            $t->timestamps();
        });

        Schema::create('drug_supply_orders', function (Blueprint $t) {
            $t->id();
            $t->string('order_no')->unique();
            $t->enum('order_type', ['regular', 'narcotic', 'psychotropic', 'precursor'])->index();
            $t->date('order_date');
            $t->string('supplier_name');
            $t->string('supplier_address')->nullable();
            $t->string('supplier_license_no')->nullable();
            $t->string('responsible_pharmacist');
            $t->string('pharmacist_sipa_no')->nullable();
            $t->enum('status', ['draft', 'sent', 'received', 'cancelled'])->default('draft')->index();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('drug_supply_order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('drug_supply_order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('drug_id')->nullable()->constrained()->nullOnDelete();
            $t->string('drug_name');
            $t->string('dose_form')->nullable();
            $t->string('strength')->nullable();
            $t->unsignedInteger('quantity');
            $t->string('unit')->nullable();
            $t->timestamps();
        });

        Schema::create('drug_destructions', function (Blueprint $t) {
            $t->id();
            $t->string('destruction_no')->unique();
            $t->date('destruction_date');
            $t->string('location');
            $t->string('method');
            $t->string('responsible_pharmacist');
            $t->string('witness_name_1')->nullable();
            $t->string('witness_name_2')->nullable();
            $t->string('witness_role_1')->nullable();
            $t->string('witness_role_2')->nullable();
            $t->text('reason')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('drug_destruction_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('drug_destruction_id')->constrained()->cascadeOnDelete();
            $t->foreignId('drug_id')->nullable()->constrained()->nullOnDelete();
            $t->string('drug_name');
            $t->string('batch_no')->nullable();
            $t->date('expired_at')->nullable();
            $t->unsignedInteger('quantity');
            $t->string('unit')->nullable();
            $t->string('reason')->nullable();
            $t->timestamps();
        });

        Schema::create('cost_estimates', function (Blueprint $t) {
            $t->id();
            $t->string('estimate_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->date('estimate_date');
            $t->string('procedure_name');
            $t->decimal('total_amount', 14, 2)->default(0);
            $t->enum('status', ['draft', 'sent', 'approved', 'rejected', 'completed'])->default('draft')->index();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('cost_estimate_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('cost_estimate_id')->constrained()->cascadeOnDelete();
            $t->string('description');
            $t->unsignedInteger('quantity')->default(1);
            $t->string('unit')->nullable();
            $t->decimal('unit_price', 14, 2)->default(0);
            $t->decimal('subtotal', 14, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('insurance_claims', function (Blueprint $t) {
            $t->id();
            $t->string('claim_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $t->string('insurance_provider');
            $t->string('policy_number')->nullable();
            $t->enum('claim_type', ['outpatient', 'inpatient', 'emergency', 'maternity'])->default('outpatient');
            $t->date('service_date');
            $t->date('claim_date');
            $t->string('diagnosis_code')->nullable();
            $t->string('diagnosis_text')->nullable();
            $t->decimal('claimed_amount', 14, 2)->default(0);
            $t->decimal('approved_amount', 14, 2)->nullable();
            $t->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'paid'])->default('draft')->index();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('discharge_summaries', function (Blueprint $t) {
            $t->id();
            $t->string('summary_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('medical_record_id')->nullable()->constrained()->nullOnDelete();
            $t->dateTime('admission_date');
            $t->dateTime('discharge_date');
            $t->string('admission_diagnosis');
            $t->string('discharge_diagnosis');
            $t->text('chief_complaint')->nullable();
            $t->text('history')->nullable();
            $t->text('physical_exam')->nullable();
            $t->text('investigations')->nullable();
            $t->text('treatment')->nullable();
            $t->text('progress')->nullable();
            $t->text('discharge_medication')->nullable();
            $t->text('follow_up')->nullable();
            $t->enum('discharge_condition', ['recovered', 'improved', 'unchanged', 'worsened', 'died'])->default('improved');
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('patient_screenings', function (Blueprint $t) {
            $t->id();
            $t->string('screening_no')->unique();
            $t->enum('type', ['fall_risk', 'pain', 'nutrition', 'pediatric_fall'])->index();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->dateTime('screened_at');
            $t->json('answers')->nullable();
            $t->unsignedSmallInteger('score')->default(0);
            $t->enum('risk_level', ['low', 'moderate', 'high'])->default('low');
            $t->text('intervention')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_screenings');
        Schema::dropIfExists('discharge_summaries');
        Schema::dropIfExists('insurance_claims');
        Schema::dropIfExists('cost_estimate_items');
        Schema::dropIfExists('cost_estimates');
        Schema::dropIfExists('drug_destruction_items');
        Schema::dropIfExists('drug_destructions');
        Schema::dropIfExists('drug_supply_order_items');
        Schema::dropIfExists('drug_supply_orders');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('informed_consents');
        Schema::dropIfExists('medical_certificates');
    }
};
