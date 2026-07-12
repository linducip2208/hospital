<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_code', 20)->unique()->nullable()->comment('NIP/Nomor Induk Pegawai');
            $table->string('position')->nullable()->comment('Jabatan');
            $table->string('department')->nullable()->comment('Departemen');
            $table->date('join_date')->nullable()->comment('Tanggal bergabung');
            $table->decimal('base_salary', 12, 2)->default(0)->comment('Gaji pokok');
            $table->string('bank_name')->nullable()->comment('Nama bank');
            $table->string('bank_account')->nullable()->comment('Nomor rekening');
            $table->string('bpjs_tk')->nullable()->comment('BPJS Ketenagakerjaan');
            $table->string('tax_number')->nullable()->comment('NPWP');
            $table->enum('employment_status', ['permanent', 'contract', 'probation', 'intern', 'resigned'])->default('permanent')->comment('Status kepegawaian');
            $table->string('education_level')->nullable()->comment('Pendidikan terakhir');
            $table->string('emergency_contact')->nullable()->comment('Kontak darurat');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
