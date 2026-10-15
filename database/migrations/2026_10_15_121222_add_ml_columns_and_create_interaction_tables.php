<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah 2 kolom ke rekomendasi_log
        Schema::table('rekomendasi_log', function (Blueprint $t) {
            $t->string('model_version')->nullable()->after('alasan');
            $t->unsignedInteger('position')->nullable()->after('model_version');
        });

        // 2. User Interactions (implicit + explicit actions)
        Schema::create('user_interactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('item_type');        // 'lowongan' | 'pelatihan'
            $t->unsignedBigInteger('item_id');
            $t->string('action');           // 'view' | 'click' | 'apply' | 'daftar' | 'skip' | 'save'
            $t->string('session_id')->nullable();
            $t->string('source')->nullable(); // 'rekomendasi' | 'search' | 'browse' | 'external'
            $t->json('metadata')->nullable(); // { "rank": 1, "model": "lightfm_v1" }
            $t->timestamp('event_at')->useCurrent();
            $t->timestamps();
            $t->index(['user_id', 'item_type', 'item_id']);
            $t->index(['action', 'event_at']);
        });

        // 3. User Feedback (explicit labels)
        Schema::create('user_feedback', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('item_type');
            $t->unsignedBigInteger('item_id');
            $t->tinyInteger('label');       // 1=like/suka, 0=dislike/tidak suka, -1=not relevant
            $t->tinyInteger('rating')->nullable(); // 1-5 (optional)
            $t->text('comment')->nullable();
            $t->timestamp('event_at')->useCurrent();
            $t->timestamps();
            $t->unique(['user_id', 'item_type', 'item_id']); // 1 feedback per item
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekomendasi_log', function (Blueprint $t) {
            $t->dropColumn(['model_version', 'position']);
        });
        Schema::dropIfExists('user_feedback');
        Schema::dropIfExists('user_interactions');
    }
};
