<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('period_month')->comment('Bulan 1-12');
            $table->integer('period_year')->comment('Tahun');
            $table->decimal('base_salary', 12, 2)->comment('Gaji pokok');
            $table->decimal('overtime_hours', 5, 1)->default(0)->comment('Jam lembur');
            $table->decimal('overtime_pay', 12, 2)->default(0)->comment('Upah lembur');
            $table->decimal('bonus', 12, 2)->default(0)->comment('Bonus');
            $table->decimal('deduction', 12, 2)->default(0)->comment('Potongan');
            $table->text('deduction_note')->nullable()->comment('Keterangan potongan');
            $table->decimal('total_salary', 12, 2)->comment('Total gaji');
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft')->comment('Status penggajian');
            $table->datetime('paid_at')->nullable()->comment('Tanggal pembayaran');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['period_month', 'period_year']);
            $table->unique(['user_id', 'period_month', 'period_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
