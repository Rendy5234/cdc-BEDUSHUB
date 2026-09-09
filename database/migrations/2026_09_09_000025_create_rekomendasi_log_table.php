<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tipe_item'); // lowongan|pelatihan|prodi|potensi
            $table->unsignedBigInteger('item_id');
            $table->float('skor')->nullable();
            $table->text('alasan')->nullable();
            $table->string('aksi')->nullable(); // view|apply|daftar
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_log');
    }
};
