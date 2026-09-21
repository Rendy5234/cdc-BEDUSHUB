<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->boolean('butuh_surat_lamaran')->default(false)->after('kuota_diterima');
            $table->boolean('butuh_pas_foto')->default(false)->after('butuh_surat_lamaran');
            $table->string('rasio_pas_foto')->nullable()->after('butuh_pas_foto');
        });
    }

    public function down(): void
    {
        Schema::table('lowongan', function (Blueprint $table) {
            $table->dropColumn(['butuh_surat_lamaran', 'butuh_pas_foto', 'rasio_pas_foto']);
        });
    }
};
