<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asesmen_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asesmen_id')->constrained('asesmen')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->string('tipe_jawaban'); // pilihan_ganda|skala|teks
            $table->json('opsi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asesmen_soal');
    }
};
