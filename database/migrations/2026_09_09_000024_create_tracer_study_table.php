<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_study', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status_pekerjaan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('posisi')->nullable();
            $table->string('bidang_pekerjaan')->nullable();
            $table->string('penghasilan')->nullable();
            $table->tinyInteger('relevansi_pekerjaan')->nullable(); // 1-5
            $table->tinyInteger('kepuasan_pendidikan')->nullable(); // 1-5
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_study');
    }
};
