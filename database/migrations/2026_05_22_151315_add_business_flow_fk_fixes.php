<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix FK gaps berdasarkan audit alur bisnis rumah sakit.
 * Lihat: DATABASE-FK-AUDIT.md
 *
 * Semua FK baru nullable → backward compatible dengan data existing.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. payments.medical_record_id ───
        Schema::table('payments', function (Blueprint $t) {
            if (! Schema::hasColumn('payments', 'medical_record_id')) {
                $t->foreignId('medical_record_id')->nullable()->after('appointment_id')
                    ->constrained('medical_records')->nullOnDelete();
            }
        });

        // ─── 2. drug_supply_orders.created_by ───
        Schema::table('drug_supply_orders', function (Blueprint $t) {
            if (! Schema::hasColumn('drug_supply_orders', 'created_by')) {
                $t->foreignId('created_by')->nullable()
                    ->constrained('users')->nullOnDelete();
            }
        });

        // ─── 3. drug_destructions.created_by + witnessed_by ───
        Schema::table('drug_destructions', function (Blueprint $t) {
            if (! Schema::hasColumn('drug_destructions', 'created_by')) {
                $t->foreignId('created_by')->nullable()
                    ->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('drug_destructions', 'witnessed_by')) {
                $t->foreignId('witnessed_by')->nullable()
                    ->constrained('users')->nullOnDelete();
            }
        });

        // ─── 4. patient_screenings.medical_record_id → nullable ───
        // Skrining biasanya sebelum MR dibuat, jadi FK harus opsional.
        if (Schema::hasColumn('patient_screenings', 'medical_record_id')) {
            Schema::table('patient_screenings', function (Blueprint $t) {
                try { $t->dropForeign(['medical_record_id']); } catch (\Throwable $e) {}
            });
            Schema::table('patient_screenings', function (Blueprint $t) {
                $t->unsignedBigInteger('medical_record_id')->nullable()->change();
                $t->foreign('medical_record_id')->references('id')->on('medical_records')->nullOnDelete();
            });
        }

        // ─── 5. Pivot medical_record_treatments (M:N) ───
        if (! Schema::hasTable('medical_record_treatments')) {
            Schema::create('medical_record_treatments', function (Blueprint $t) {
                $t->id();
                $t->foreignId('medical_record_id')->constrained()->cascadeOnDelete();
                $t->foreignId('treatment_id')->constrained()->restrictOnDelete();
                $t->integer('quantity')->default(1);
                $t->decimal('unit_price', 12, 2)->default(0); // snapshot harga saat tindakan
                $t->text('notes')->nullable();
                $t->timestamps();
                $t->unique(['medical_record_id', 'treatment_id'], 'mr_treatment_unique');
                $t->index('treatment_id');
            });
        }

        // ─── 6. prescriptions.appointment_id ───
        Schema::table('prescriptions', function (Blueprint $t) {
            if (! Schema::hasColumn('prescriptions', 'appointment_id')) {
                $t->foreignId('appointment_id')->nullable()->after('medical_record_id')
                    ->constrained('appointments')->nullOnDelete();
            }
        });

        // ─── 7. vital_signs_records.appointment_id & nursing_cares.appointment_id ───
        Schema::table('vital_signs_records', function (Blueprint $t) {
            if (! Schema::hasColumn('vital_signs_records', 'appointment_id')) {
                $t->foreignId('appointment_id')->nullable()
                    ->constrained('appointments')->nullOnDelete();
            }
        });
        Schema::table('nursing_cares', function (Blueprint $t) {
            if (! Schema::hasColumn('nursing_cares', 'appointment_id')) {
                $t->foreignId('appointment_id')->nullable()
                    ->constrained('appointments')->nullOnDelete();
            }
        });

        // ─── 8. appointments.polyclinic_id ───
        Schema::table('appointments', function (Blueprint $t) {
            if (! Schema::hasColumn('appointments', 'polyclinic_id')) {
                $t->foreignId('polyclinic_id')->nullable()->after('doctor_id')
                    ->constrained('polyclinics')->nullOnDelete();
            }
        });

        // ─── 9. referrals.medical_record_id ───
        Schema::table('referrals', function (Blueprint $t) {
            if (! Schema::hasColumn('referrals', 'medical_record_id')) {
                $t->foreignId('medical_record_id')->nullable()
                    ->constrained('medical_records')->nullOnDelete();
            }
        });

        // ─── 10. lab_tests & radiologies → tambah appointment_id + medical_record_id ───
        Schema::table('lab_tests', function (Blueprint $t) {
            if (! Schema::hasColumn('lab_tests', 'appointment_id')) {
                $t->foreignId('appointment_id')->nullable()
                    ->constrained('appointments')->nullOnDelete();
            }
            if (! Schema::hasColumn('lab_tests', 'medical_record_id')) {
                $t->foreignId('medical_record_id')->nullable()
                    ->constrained('medical_records')->nullOnDelete();
            }
        });
        Schema::table('radiologies', function (Blueprint $t) {
            if (! Schema::hasColumn('radiologies', 'appointment_id')) {
                $t->foreignId('appointment_id')->nullable()
                    ->constrained('appointments')->nullOnDelete();
            }
            if (! Schema::hasColumn('radiologies', 'medical_record_id')) {
                $t->foreignId('medical_record_id')->nullable()
                    ->constrained('medical_records')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_record_treatments');

        $drops = [
            'payments'            => ['medical_record_id'],
            'drug_supply_orders'  => ['created_by'],
            'drug_destructions'   => ['created_by', 'witnessed_by'],
            'prescriptions'       => ['appointment_id'],
            'vital_signs_records' => ['appointment_id'],
            'nursing_cares'       => ['appointment_id'],
            'appointments'        => ['polyclinic_id'],
            'referrals'           => ['medical_record_id'],
            'lab_tests'           => ['appointment_id', 'medical_record_id'],
            'radiologies'         => ['appointment_id', 'medical_record_id'],
        ];

        foreach ($drops as $table => $cols) {
            if (! Schema::hasTable($table)) continue;
            Schema::table($table, function (Blueprint $t) use ($table, $cols) {
                foreach ($cols as $col) {
                    if (Schema::hasColumn($table, $col)) {
                        try { $t->dropForeign([$col]); } catch (\Throwable $e) {}
                        $t->dropColumn($col);
                    }
                }
            });
        }
    }
};
