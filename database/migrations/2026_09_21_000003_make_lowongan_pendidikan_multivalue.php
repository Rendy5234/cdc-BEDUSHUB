<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 'pendidikan' kini dapat bernilai ganda (array). Konversi nilai lama (string tunggal)
        // menjadi array JSON agar sesuai dengan cast 'array' pada model Lowongan.
        DB::table('lowongan')
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '!=', '')
            ->get()
            ->each(function ($row) {
                $value = $row->pendidikan;

                if (is_string($value) && ! str_starts_with(trim($value), '[')) {
                    DB::table('lowongan')
                        ->where('id', $row->id)
                        ->update(['pendidikan' => json_encode([$value])]);
                }
            });
    }

    public function down(): void
    {
        // Nilai ganda tidak dapat dipulihkan secara pasti menjadi string tunggal.
    }
};
