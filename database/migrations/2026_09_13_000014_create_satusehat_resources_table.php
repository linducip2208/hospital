<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satusehat_resources', function (Blueprint $table) {
            $table->id();
            $table->string('local_type');
            $table->unsignedBigInteger('local_id');
            $table->string('resource_type');
            $table->string('resource_id')->nullable();
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending')->index();
            $table->string('payload_hash', 64)->nullable();
            $table->json('last_payload')->nullable();
            $table->dateTime('last_synced_at')->nullable();
            $table->text('last_error')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamps();
            $table->unique(['local_type', 'local_id', 'resource_type']);
            $table->index(['resource_type', 'resource_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satusehat_resources');
    }
};
