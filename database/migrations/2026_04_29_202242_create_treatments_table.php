<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->nullable()->comment('Kategori tindakan');
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('duration_minutes')->nullable()->comment('Durasi tindakan (menit)');
            $table->text('notes')->nullable();
            $table->json('requirements')->nullable()->comment('Persyaratan/persiapan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
