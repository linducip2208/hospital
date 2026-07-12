<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. HospitalBed — per-bed tracking under rooms
        Schema::create('hospital_beds', function (Blueprint $t) {
            $t->id();
            $t->foreignId('room_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->string('bed_code')->unique();
            $t->string('label')->nullable();
            $t->enum('status', ['available', 'occupied', 'reserved', 'cleaning', 'maintenance', 'blocked'])->default('available')->index();
            $t->foreignId('current_patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $t->dateTime('occupied_since')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        // 2. PatientSafetyIncident — KTD/KNC/KPC/Sentinel
        Schema::create('patient_safety_incidents', function (Blueprint $t) {
            $t->id();
            $t->string('incident_no')->unique();
            $t->enum('incident_type', ['knc', 'ktc', 'ktd', 'kpc', 'sentinel'])->index();
            $t->enum('severity', ['none', 'minor', 'moderate', 'major', 'catastrophic'])->index();
            $t->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $t->dateTime('occurred_at');
            $t->dateTime('reported_at');
            $t->string('location');
            $t->text('description');
            $t->text('immediate_action')->nullable();
            $t->text('root_cause')->nullable();
            $t->text('corrective_action')->nullable();
            $t->enum('status', ['reported', 'investigating', 'closed'])->default('reported')->index();
            $t->timestamps();
            $t->softDeletes();
        });

        // 3. CodeBlueActivation
        Schema::create('code_blue_activations', function (Blueprint $t) {
            $t->id();
            $t->string('code_no')->unique();
            $t->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $t->string('location');
            $t->dateTime('activation_time');
            $t->dateTime('team_arrival_time')->nullable();
            $t->dateTime('return_circulation_time')->nullable();
            $t->dateTime('end_time')->nullable();
            $t->enum('outcome', ['rosc', 'died', 'transferred', 'ongoing'])->default('ongoing')->index();
            $t->string('team_leader')->nullable();
            $t->text('initial_rhythm')->nullable();
            $t->text('interventions')->nullable();
            $t->text('medications_given')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        // 4. DietOrder
        Schema::create('diet_orders', function (Blueprint $t) {
            $t->id();
            $t->string('order_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->date('order_date');
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->enum('diet_type', ['regular', 'soft', 'liquid', 'puree', 'tube_feed', 'parenteral', 'diabetic', 'low_salt', 'low_protein', 'high_protein', 'low_fat', 'gluten_free', 'custom'])->index();
            $t->string('texture')->nullable();
            $t->unsignedInteger('calories')->nullable();
            $t->json('restrictions')->nullable();
            $t->text('special_instructions')->nullable();
            $t->enum('status', ['active', 'paused', 'discontinued', 'completed'])->default('active')->index();
            $t->timestamps();
            $t->softDeletes();
        });

        // 5. Odontogram — dental record
        Schema::create('odontograms', function (Blueprint $t) {
            $t->id();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();
            $t->date('exam_date');
            $t->json('teeth_state')->nullable();
            $t->text('general_findings')->nullable();
            $t->text('treatment_plan')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        // 6. PatientFeedback
        Schema::create('patient_feedbacks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $t->date('visit_date')->nullable();
            $t->string('service_type')->nullable();
            $t->unsignedTinyInteger('rating_overall')->nullable();
            $t->unsignedTinyInteger('rating_doctor')->nullable();
            $t->unsignedTinyInteger('rating_nurse')->nullable();
            $t->unsignedTinyInteger('rating_facility')->nullable();
            $t->unsignedTinyInteger('rating_cleanliness')->nullable();
            $t->unsignedTinyInteger('rating_speed')->nullable();
            $t->boolean('would_recommend')->nullable();
            $t->text('positive')->nullable();
            $t->text('negative')->nullable();
            $t->text('suggestion')->nullable();
            $t->boolean('is_anonymous')->default(false);
            $t->string('respondent_name')->nullable();
            $t->string('respondent_contact')->nullable();
            $t->enum('status', ['new', 'reviewed', 'responded', 'closed'])->default('new')->index();
            $t->timestamps();
            $t->softDeletes();
        });

        // 7. ClinicalPathway — pathway templates
        Schema::create('clinical_pathways', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->string('name');
            $t->string('diagnosis_code')->nullable();
            $t->string('diagnosis')->nullable();
            $t->unsignedSmallInteger('expected_los_days')->nullable();
            $t->json('phases')->nullable();
            $t->text('inclusion_criteria')->nullable();
            $t->text('exclusion_criteria')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        // 8. InfectionSurveillance — HAI tracking
        Schema::create('infection_surveillances', function (Blueprint $t) {
            $t->id();
            $t->string('case_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->enum('infection_type', ['vap', 'clabsi', 'cauti', 'ssi', 'phlebitis', 'decubitus', 'other'])->index();
            $t->date('detection_date');
            $t->date('onset_date')->nullable();
            $t->string('site');
            $t->string('organism')->nullable();
            $t->text('symptoms')->nullable();
            $t->text('antibiotic_therapy')->nullable();
            $t->text('intervention')->nullable();
            $t->enum('outcome', ['resolved', 'ongoing', 'died'])->default('ongoing');
            $t->timestamps();
            $t->softDeletes();
        });

        // 9. EquipmentMaintenance — alat medis
        Schema::create('equipment_maintenances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $t->date('scheduled_date');
            $t->date('performed_date')->nullable();
            $t->enum('maintenance_type', ['preventive', 'corrective', 'calibration', 'inspection'])->default('preventive')->index();
            $t->string('performer')->nullable();
            $t->text('description')->nullable();
            $t->text('findings')->nullable();
            $t->text('action')->nullable();
            $t->decimal('cost', 14, 2)->default(0);
            $t->enum('result', ['ok', 'needs_repair', 'replaced', 'failed'])->nullable();
            $t->date('next_due_date')->nullable();
            $t->enum('status', ['scheduled', 'in_progress', 'done', 'cancelled'])->default('scheduled')->index();
            $t->timestamps();
            $t->softDeletes();
        });

        // 10. ICUMonitoring — high-frequency vitals
        Schema::create('icu_monitorings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('hospital_bed_id')->nullable()->constrained()->nullOnDelete();
            $t->dateTime('recorded_at');
            $t->decimal('temperature', 4, 1)->nullable();
            $t->unsignedSmallInteger('hr')->nullable();
            $t->unsignedSmallInteger('rr')->nullable();
            $t->unsignedSmallInteger('sbp')->nullable();
            $t->unsignedSmallInteger('dbp')->nullable();
            $t->unsignedSmallInteger('map')->nullable();
            $t->unsignedTinyInteger('spo2')->nullable();
            $t->unsignedTinyInteger('gcs')->nullable();
            $t->decimal('cvp', 5, 2)->nullable();
            $t->json('ventilator')->nullable();
            $t->json('drips')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->index(['patient_id', 'recorded_at']);
        });

        // 11. Telemedicine — video consult records
        Schema::create('telemedicine_sessions', function (Blueprint $t) {
            $t->id();
            $t->string('session_no')->unique();
            $t->foreignId('patient_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('doctor_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->dateTime('scheduled_at');
            $t->dateTime('started_at')->nullable();
            $t->dateTime('ended_at')->nullable();
            $t->string('platform')->nullable();
            $t->string('meeting_url')->nullable();
            $t->string('meeting_id')->nullable();
            $t->enum('status', ['scheduled', 'ongoing', 'completed', 'no_show', 'cancelled'])->default('scheduled')->index();
            $t->text('chief_complaint')->nullable();
            $t->text('assessment')->nullable();
            $t->text('plan')->nullable();
            $t->decimal('fee', 14, 2)->default(0);
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicine_sessions');
        Schema::dropIfExists('icu_monitorings');
        Schema::dropIfExists('equipment_maintenances');
        Schema::dropIfExists('infection_surveillances');
        Schema::dropIfExists('clinical_pathways');
        Schema::dropIfExists('patient_feedbacks');
        Schema::dropIfExists('odontograms');
        Schema::dropIfExists('diet_orders');
        Schema::dropIfExists('code_blue_activations');
        Schema::dropIfExists('patient_safety_incidents');
        Schema::dropIfExists('hospital_beds');
    }
};
