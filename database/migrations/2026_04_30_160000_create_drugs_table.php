<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable()->comment('Jenis obat: Tablet, Sirup, Salep, Injeksi');
            $table->string('unit')->nullable()->comment('Satuan: Strip, Botol, Ampul');
            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
