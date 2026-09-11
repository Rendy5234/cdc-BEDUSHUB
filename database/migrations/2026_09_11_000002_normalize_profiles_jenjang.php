<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 'alumni' bukan jenjang pendidikan; sudah diwakili oleh role (User::ROLE_ALUMNI) dan status (lulus).
        DB::table('profiles')->where('jenjang', 'alumni')->update(['jenjang' => null]);
    }

    public function down(): void
    {
        // Nilai lama ('alumni') tidak dapat dipulihkan secara pasti.
    }
};
