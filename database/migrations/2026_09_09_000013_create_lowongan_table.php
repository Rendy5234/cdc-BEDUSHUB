<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaan')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->text('kualifikasi')->nullable();
            $table->string('tipe_pekerjaan')->nullable();
            $table->string('lokasi')->nullable();
            $table->bigInteger('gaji_min')->nullable();
            $table->bigInteger('gaji_max')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('status')->default('aktif'); // aktif|nonaktif
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
