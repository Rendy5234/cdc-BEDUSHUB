<?php

namespace App\Services;

use App\Enums\SkillLevel;
use App\Models\Lowongan;
use App\Models\Notifikasi;
use App\Models\Pelatihan;
use App\Models\PotensiDaerah;
use App\Models\RekomendasiLog;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Kontrak data untuk mesin rekomendasi (AI).
 *
 * Layanan ini menyiapkan konteks user dan katalog item dalam bentuk terstruktur
 * agar mudah dikonsumsi mesin AI (dikembangkan terpisah), serta menyediakan
 * jalur tulis hasil rekomendasi ke RekomendasiLog + Notifikasi.
 */
class RekomendasiService
{
    /**
     * Konteks lengkap seorang user (profil, skill, minat, hasil asesmen).
     *
     * @return array<string, mixed>
     */
    public function userContext(User $user): array
    {
        $profile = $user->profile;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'profile' => $profile ? [
                'jenis_kelamin' => $profile->jenis_kelamin,
                'tanggal_lahir' => $profile->tanggal_lahir?->toDateString(),
                'domisili_kecamatan' => $profile->domisili_kecamatan,
                'jenjang' => $profile->jenjang,
                'institusi' => $profile->institusi?->nama,
                'jurusan' => $profile->jurusan?->nama,
                'prodi' => $profile->prodi?->nama,
                'tahun_lulus' => $profile->tahun_lulus,
                'status' => $profile->status,
            ] : null,
            'skills' => $user->userSkills->map(fn ($us) => [
                'skill_id' => $us->skill_id,
                'nama' => $us->skill?->nama,
                'kategori' => $us->skill?->kategori,
                'level' => $us->level,
                'level_order' => SkillLevel::tryFrom($us->level)?->order(),
            ])->values()->all(),
            'minat' => $user->minat->map(fn ($m) => [
                'id' => $m->id,
                'nama' => $m->nama,
            ])->values()->all(),
            'asesmen' => $this->asesmenResults($user),
        ];
    }

    /**
     * Hasil asesmen per asesmen beserta pemetaan soal -> skill/minat.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function asesmenResults(User $user): array
    {
        return $user->asesmenJawabans
            ->groupBy('asesmen_id')
            ->map(function (Collection $jawabans, $asesmenId): array {
                $asesmen = $jawabans->first()->asesmen;

                return [
                    'asesmen_id' => $asesmenId,
                    'judul' => $asesmen?->judul,
                    'tipe' => $asesmen?->tipe,
                    'jawaban' => $jawabans->map(fn ($j) => [
                        'soal_id' => $j->soal_id,
                        'pertanyaan' => $j->soal?->pertanyaan,
                        'skill_id' => $j->soal?->skill_id,
                        'kategori_minat_id' => $j->soal?->kategori_minat_id,
                        'jawaban' => $j->jawaban,
                        'skor' => $j->skor,
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Katalog item yang bisa direkomendasikan (big data).
     *
     * @return array<string, mixed>
     */
    public function catalog(): array
    {
        return [
            'lowongan' => Lowongan::query()
                ->with(['perusahaan', 'skills', 'minat'])
                ->tersediaUntukUser()
                ->get()
                ->map(fn (Lowongan $l) => [
                    'id' => $l->id,
                    'judul' => $l->judul,
                    'perusahaan' => $l->perusahaan?->nama,
                    'tipe_pekerjaan' => $l->tipe_pekerjaan,
                    'lokasi' => $l->lokasi,
                    'gaji_min' => $l->gaji_min,
                    'gaji_max' => $l->gaji_max,
                    'pendidikan' => $l->pendidikan,
                    'kuota' => $l->kuota,
                    'tanggal_mulai' => $l->tanggal_mulai?->toDateString(),
                    'tanggal_berakhir' => $l->tanggal_berakhir?->toDateString(),
                    'skills' => $l->skills->map(fn ($s) => [
                        'skill_id' => $s->id,
                        'nama' => $s->nama,
                        'level_min' => $s->pivot->level_min ?? null,
                    ])->values()->all(),
                    'minat' => $l->minat->map(fn ($m) => [
                        'id' => $m->id,
                        'nama' => $m->nama,
                    ])->values()->all(),
                ])->values()->all(),
            'pelatihan' => Pelatihan::query()
                ->with(['skills', 'minat'])
                ->where('status', 'published')
                ->get()
                ->map(fn (Pelatihan $p) => [
                    'id' => $p->id,
                    'judul' => $p->judul,
                    'topik' => $p->topik,
                    'level' => $p->level,
                    'level_order' => SkillLevel::tryFrom($p->level)?->order(),
                    'instruktur' => $p->instruktur,
                    'tanggal_mulai' => $p->tanggal_mulai?->toDateString(),
                    'tanggal_selesai' => $p->tanggal_selesai?->toDateString(),
                    'kuota' => $p->kuota,
                    'skills' => $p->skills->map(fn ($s) => [
                        'skill_id' => $s->id,
                        'nama' => $s->nama,
                    ])->values()->all(),
                    'minat' => $p->minat->map(fn ($m) => [
                        'id' => $m->id,
                        'nama' => $m->nama,
                    ])->values()->all(),
                ])->values()->all(),
            'potensi_daerah' => PotensiDaerah::query()
                ->with(['sektor', 'skills'])
                ->get()
                ->map(fn (PotensiDaerah $p) => [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'sektor' => $p->sektor?->nama,
                    'lokasi_kecamatan' => $p->lokasi_kecamatan,
                    'peluang_usaha' => $p->peluang_usaha,
                    'skills' => $p->skills->map(fn ($s) => [
                        'skill_id' => $s->id,
                        'nama' => $s->nama,
                    ])->values()->all(),
                ])->values()->all(),
        ];
    }

    /**
     * Payload gabungan (user + katalog) yang siap diberikan ke mesin AI.
     *
     * @return array<string, mixed>
     */
    public function recommendationPayload(User $user): array
    {
        return [
            'user' => $this->userContext($user),
            'catalog' => $this->catalog(),
        ];
    }

    /**
     * Menyimpan satu hasil rekomendasi ke log.
     */
    public function storeRecommendation(
        User $user,
        string $tipeItem,
        int $itemId,
        float $skor,
        string $alasan,
        string $aksi = 'view',
    ): RekomendasiLog {
        return RekomendasiLog::create([
            'user_id' => $user->id,
            'tipe_item' => $tipeItem,
            'item_id' => $itemId,
            'skor' => $skor,
            'alasan' => $alasan,
            'aksi' => $aksi,
        ]);
    }

    /**
     * Baseline rule-based (overlap skill) — placeholder sebelum AI final.
     *
     * @return array<int, array<string, mixed>>
     */
    public function baselineRecommend(User $user, int $limit = 5): array
    {
        $payload = $this->recommendationPayload($user);

        $userSkills = collect($payload['user']['skills'])->keyBy('skill_id');
        $recommendations = [];

        foreach ($payload['catalog']['lowongan'] as $lowongan) {
            $skor = $this->skillOverlapScore($userSkills, collect($lowongan['skills']));
            if ($skor <= 0) {
                continue;
            }

            $recommendations[] = [
                'tipe_item' => 'lowongan',
                'item_id' => $lowongan['id'],
                'judul' => $lowongan['judul'],
                'skor' => $skor,
                'alasan' => $this->alasanSkill($skor),
            ];
        }

        foreach ($payload['catalog']['pelatihan'] as $pelatihan) {
            $skor = $this->skillOverlapScore($userSkills, collect($pelatihan['skills']));
            if ($skor <= 0) {
                continue;
            }

            $recommendations[] = [
                'tipe_item' => 'pelatihan',
                'item_id' => $pelatihan['id'],
                'judul' => $pelatihan['judul'],
                'skor' => $skor,
                'alasan' => $this->alasanSkill($skor),
            ];
        }

        $recommendations = collect($recommendations)
            ->sortByDesc('skor')
            ->take($limit)
            ->values();

        foreach ($recommendations as $rec) {
            $this->storeRecommendation(
                $user,
                $rec['tipe_item'],
                $rec['item_id'],
                (float) $rec['skor'],
                $rec['alasan'],
            );
        }

        if ($recommendations->isNotEmpty()) {
            $this->notify(
                $user,
                'Rekomendasi baru',
                'Ada '.$recommendations->count().' rekomendasi loker & pelatihan untuk Anda.',
            );
        }

        return $recommendations->all();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $userSkills
     * @param  Collection<int, array<string, mixed>>  $itemSkills
     */
    protected function skillOverlapScore(Collection $userSkills, Collection $itemSkills): float
    {
        $total = 0.0;

        foreach ($itemSkills as $itemSkill) {
            $userSkill = $userSkills->get($itemSkill['skill_id']);
            if (! $userSkill) {
                continue;
            }

            $total += 1.0;

            $levelMin = isset($itemSkill['level_min'])
                ? SkillLevel::tryFrom($itemSkill['level_min'])
                : null;
            $userLevel = SkillLevel::tryFrom($userSkill['level']);

            if ($levelMin && $userLevel && $userLevel->order() >= $levelMin->order()) {
                $total += 0.5;
            }
        }

        return round($total, 2);
    }

    protected function alasanSkill(float $skor): string
    {
        return 'Kecocokan skill dengan profil Anda (skor '.number_format($skor, 2).').';
    }

    public function notify(User $user, string $judul, string $isi): Notifikasi
    {
        return Notifikasi::create([
            'user_id' => $user->id,
            'judul' => $judul,
            'isi' => $isi,
            'dibaca' => false,
        ]);
    }
}
