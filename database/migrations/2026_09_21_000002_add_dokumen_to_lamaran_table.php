<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lamaran', function (Blueprint $table) {
            $table->string('ijazah')->nullable()->after('cv');
            $table->string('surat_lamaran')->nullable()->after('ijazah');
            $table->string('pas_foto')->nullable()->after('surat_lamaran');
            $table->json('dokumen_pendukung')->nullable()->after('pas_foto');
        });
    }

    public function down(): void
    {
        Schema::table('lamaran', function (Blueprint $table) {
            $table->dropColumn(['ijazah', 'surat_lamaran', 'pas_foto', 'dokumen_pendukung']);
        });
    }
};
