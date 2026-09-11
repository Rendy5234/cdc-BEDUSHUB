<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->text('benefit')->nullable();
            $table->string('pendidikan')->nullable();
            $table->integer('kuota')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->string('thumbnail')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropColumn(['benefit', 'pendidikan', 'kuota', 'tanggal_mulai', 'thumbnail']);
        });
    }
};
