<?php

namespace Tests\Feature;

use App\Enums\SkillLevel;
use App\Models\Lowongan;
use App\Models\Perusahaan;
use App\Models\Skill;
use App\Models\User;
use App\Services\RekomendasiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekomendasiServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User dengan level skill di bawah syarat level_min lowongan
     * tidak harusnya muncul di rekomendasi hasil rule-based.
     */
    public function test_lowongan_tidak_dicantumkan_jika_level_skill_user_di_bawah_level_min(): void
    {
        $skill = Skill::create(['nama' => 'Laravel', 'kategori' => 'Backend']);

        $userPerusahaan = User::factory()->create();
        $perusahaan = Perusahaan::create([
            'user_id' => $userPerusahaan->id,
            'nama' => 'PT Test',
            'status_kerjasama' => 'aktif',
        ]);
        $lowongan = Lowongan::create([
            'perusahaan_id' => $perusahaan->id,
            'judul' => 'Backend Laravel Menengah',
            'deskripsi' => 'butuh laravel menengah',
            'status' => 'aktif',
            'tipe_pekerjaan' => 'full-time',
            'lokasi' => 'Bojonegoro',
        ]);
        $lowongan->skills()->attach($skill->id, ['level_min' => SkillLevel::MENENGAH->value]);

        $user = User::factory()->create();
        $user->userSkills()->create(['skill_id' => $skill->id, 'level' => SkillLevel::PEMULA->value]);

        $recommendations = app(RekomendasiService::class)->baselineRecommend($user, limit: 10);

        $lowonganIds = collect($recommendations)
            ->where('tipe_item', 'lowongan')
            ->pluck('item_id')
            ->all();

        $this->assertNotContains($lowongan->id, $lowonganIds);
    }

    /**
     * User dengan level skill sama dengan atau di atas level_min
     * lowongan harusnya tetap muncul di rekomendasi.
     */
    public function test_lowongan_dicantumkan_jika_level_skill_user_memenuhi_syarat(): void
    {
        $skill = Skill::create(['nama' => 'Laravel', 'kategori' => 'Backend']);

        $userPerusahaan = User::factory()->create();
        $perusahaan = Perusahaan::create([
            'user_id' => $userPerusahaan->id,
            'nama' => 'PT Test',
            'status_kerjasama' => 'aktif',
        ]);
        $lowongan = Lowongan::create([
            'perusahaan_id' => $perusahaan->id,
            'judul' => 'Backend Laravel Pemula',
            'deskripsi' => 'butuh laravel pemula',
            'status' => 'aktif',
            'tipe_pekerjaan' => 'full-time',
            'lokasi' => 'Bojonegoro',
        ]);
        $lowongan->skills()->attach($skill->id, ['level_min' => SkillLevel::PEMULA->value]);

        $user = User::factory()->create();
        $user->userSkills()->create(['skill_id' => $skill->id, 'level' => SkillLevel::MAHIR->value]);

        $recommendations = app(RekomendasiService::class)->baselineRecommend($user, limit: 10);

        $lowonganIds = collect($recommendations)
            ->where('tipe_item', 'lowongan')
            ->pluck('item_id')
            ->all();

        $this->assertContains($lowongan->id, $lowonganIds);
    }
}
