<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asesmen_soal', function (Blueprint $table) {
            $table->foreignId('skill_id')
                ->nullable()
                ->after('asesmen_id')
                ->constrained('skills')
                ->nullOnDelete();

            $table->foreignId('kategori_minat_id')
                ->nullable()
                ->after('skill_id')
                ->constrained('kategori_minat')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asesmen_soal', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_minat_id');
            $table->dropConstrainedForeignId('skill_id');
        });
    }
};
