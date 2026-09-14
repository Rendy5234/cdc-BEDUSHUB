<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->string('jenis')->nullable();
            $table->string('jam_mulai')->nullable();
            $table->string('jam_selesai')->nullable();
            $table->string('tempat')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('link_materi')->nullable();
            $table->string('link_sertifikat')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pelatihan', function (Blueprint $table) {
            $table->dropColumn([
                'jenis',
                'jam_mulai',
                'jam_selesai',
                'tempat',
                'thumbnail',
                'link_materi',
                'link_sertifikat',
            ]);
        });
    }
};
