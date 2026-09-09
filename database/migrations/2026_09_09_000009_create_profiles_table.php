<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('jenis_kelamin')->nullable(); // l|p
            $table->date('tanggal_lahir')->nullable();
            $table->string('domisili_kecamatan')->nullable();
            $table->string('jenjang')->nullable(); // smk|d3|s1|alumni
            $table->foreignId('institusi_id')->nullable()->constrained('institusi')->nullOnDelete();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->nullOnDelete();
            $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->nullOnDelete();
            $table->integer('tahun_lulus')->nullable();
            $table->string('status')->default('aktif'); // aktif|lulus
            $table->string('no_telp')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
