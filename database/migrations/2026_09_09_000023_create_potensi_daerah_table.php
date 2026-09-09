<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('potensi_daerah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sektor_id')->constrained('sektor_unggulan')->cascadeOnDelete();
            $table->string('nama');
            $table->string('lokasi_kecamatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('peluang_usaha')->nullable();
            $table->json('kebutuhan_skill')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('potensi_daerah');
    }
};
