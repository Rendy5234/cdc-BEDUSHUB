<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institusi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jenis'); // smk|sma|pt
            $table->text('alamat')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('status')->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institusi');
    }
};
