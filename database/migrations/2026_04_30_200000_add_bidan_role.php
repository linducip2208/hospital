<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
                'admin','doctor','staff','nurse','midwife','pharmacist','cashier','lab_technician'
            ) DEFAULT 'staff'");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
                'admin','doctor','staff','nurse','pharmacist','cashier','lab_technician'
            ) DEFAULT 'staff'");
        }
    }
};
