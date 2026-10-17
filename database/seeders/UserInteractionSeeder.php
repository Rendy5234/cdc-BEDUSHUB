<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use App\Models\Pelatihan;
use App\Models\User;
use App\Models\UserFeedback;
use App\Models\UserInteraction;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;

/**
 * Seeder interaksi user-item dengan POLA yang bisa dipelajari ALS.
 *
 * Mengapa diubah:
 * Seeder lama memilih item secara acak (popularity pool), sehingga label
 * tidak berkorelasi dengan fitur apa pun. Akibatnya ALS tidak punya sinyal
 * untuk dipelajari (Precision@5 jatuh di bawah random baseline).
 *
 * Strategi baru:
 * 1. Item dipilih berbasis kecocokan skill & minat user dengan item.
 * 2. Kecocokan mendorong journey ke arah positif (apply/daftar -> label 1).
 * 3. Ketidakcocokan mendorong journey ke view-only/skip (label 0).
 * 4. Ditambah noise 10% agar tidak terlalu "perfect" (realisme).
 */
class UserInteractionSeeder extends Seeder
{
    /** Target total baris interaksi yang di-seed. */
    private const TARGET_INTERACTIONS = 5000;

    /** Probabilitas aksi positif saat skill/minat BERIRISAN. */
    private const POSITIVE_RATE_MATCH = 0.80;

    /** Probabilitas aksi positif saat TIDAK ada irisan (kebetulan klik). */
    private const POSITIVE_RATE_MISS = 0.10;

    /** Noise: peluang membalik keputusan positif/negatif. */
    private const NOISE_RATE = 0.10;

    public function run(): void
    {
        $users = User::whereIn('role', ['siswa', 'mahasiswa', 'alumni'])
            ->with(['skills', 'minat'])
            ->get();

        $lowongans = Lowongan::where('status', 'aktif')
            ->with(['skills', 'minat'])
            ->get();

        $pelatihan = Pelatihan::where('status', 'published')
            ->with(['skills', 'minat'])
            ->get();

        if ($users->isEmpty() || $lowongans->isEmpty() || $pelatihan->isEmpty()) {
            $this->command->warn('Data prerequisite kosong (users/lowongan/pelatihan). Skip seeder.');

            return;
        }

        $faker = Factory::create('id_ID');
        $sources = ['rekomendasi', 'search', 'browse', 'external'];

        $count = 0;
        $positives = 0;
        $negatives = 0;
        $matched = 0;
        $unmatched = 0;

        // Cache id skill/minat per entitas agar tidak query berulang di loop.
        $userSkillIds = $users->mapWithKeys(fn (User $u) => [$u->id => $u->skills->pluck('id')->all()]);
        $userMinatIds = $users->mapWithKeys(fn (User $u) => [$u->id => $u->minat->pluck('id')->all()]);

        $lowonganSkillIds = $lowongans->mapWithKeys(fn ($l) => [$l->id => $l->skills->pluck('id')->all()]);
        $lowonganMinatIds = $lowongans->mapWithKeys(fn ($l) => [$l->id => $l->minat->pluck('id')->all()]);

        $pelatihanSkillIds = $pelatihan->mapWithKeys(fn ($p) => [$p->id => $p->skills->pluck('id')->all()]);
        $pelatihanMinatIds = $pelatihan->mapWithKeys(fn ($p) => [$p->id => $p->minat->pluck('id')->all()]);

        // Simpan interaksi per (user, item) supaya tidak ada duplikat pasangan
        // yang justru mengacaukan agregasi label di MlExportDataset.
        $seen = [];

        $guard = 0;
        $maxGuard = self::TARGET_INTERACTIONS * 20;

        while ($count < self::TARGET_INTERACTIONS && $guard < $maxGuard) {
            $guard++;

            /** @var User $user */
            $user = $users->random();
            $isLowongan = $faker->boolean(60);

            $item = $isLowongan ? $lowongans->random() : $pelatihan->random();

            $pairKey = $user->id.'|'.($isLowongan ? 'lowongan' : 'pelatihan').'|'.$item->id;
            if (isset($seen[$pairKey])) {
                continue;
            }

            $itemSkills = $isLowongan
                ? ($lowonganSkillIds[$item->id] ?? [])
                : ($pelatihanSkillIds[$item->id] ?? []);
            $itemMinat = $isLowongan
                ? ($lowonganMinatIds[$item->id] ?? [])
                : ($pelatihanMinatIds[$item->id] ?? []);

            $skillOverlap = array_intersect($userSkillIds[$user->id] ?? [], $itemSkills);
            $minatOverlap = array_intersect($userMinatIds[$user->id] ?? [], $itemMinat);

            $hasAffinity = ! empty($skillOverlap) || ! empty($minatOverlap);
            $hasAffinity ? $matched++ : $unmatched++;

            // Keputusan dasar: kecocokan -> cenderung positif.
            $baseRate = $hasAffinity ? self::POSITIVE_RATE_MATCH : self::POSITIVE_RATE_MISS;
            $isPositive = $faker->boolean((int) round($baseRate * 100));

            // Noise: sesekali balik keputusan agar data tidak terlalu bersih.
            if ($faker->boolean((int) round(self::NOISE_RATE * 100))) {
                $isPositive = ! $isPositive;
            }

            // Batasi jumlah positif agar tidak melampaui target rasio (<=65%).
            $currentRatio = $count > 0 ? $positives / $count : 0.0;
            if ($isPositive && $currentRatio >= 0.65) {
                $isPositive = false;
            }

            $seen[$pairKey] = true;

            $sessionId = 'sess_'.$faker->uuid();
            $source = $faker->randomElement($sources);
            $eventAt = $faker->dateTimeBetween('-60 days', 'now');

            // Journey dibangun sesuai arah keputusan.
            $journey = ['view'];

            if ($isPositive) {
                // Positif: selalu lanjut click, sering apply/daftar, kadang save.
                $journey[] = 'click';
                if ($faker->boolean(85)) {
                    $journey[] = $isLowongan ? 'apply' : 'daftar';
                }
                if ($faker->boolean(15)) {
                    $journey[] = 'save';
                }
                $positives++;
            } else {
                // Negatif: jarang click, tanpa apply/daftar, kadang skip.
                if ($faker->boolean(30)) {
                    $journey[] = 'click';
                }
                if ($faker->boolean(18)) {
                    $journey[] = 'skip';
                }
                $negatives++;
            }

            foreach ($journey as $idx => $action) {
                UserInteraction::create([
                    'user_id' => $user->id,
                    'item_type' => $isLowongan ? 'lowongan' : 'pelatihan',
                    'item_id' => $item->id,
                    'action' => $action,
                    'session_id' => $sessionId,
                    'source' => $source,
                    'metadata' => ['rank' => $idx + 1, 'model' => 'pattern_v2'],
                    'event_at' => $eventAt,
                ]);
                $count++;
            }
        }

        $this->seedFeedback($faker);

        $totalPairs = $positives + $negatives;
        $posRatio = $totalPairs > 0 ? round($positives / $totalPairs * 100, 1) : 0.0;

        $this->command->info("Seeded {$count} interaksi (target ".self::TARGET_INTERACTIONS.').');
        $this->command->info("  Pasangan unik: {$totalPairs} (positif: {$positives} / negatif: {$negatives} -> {$posRatio}% positif)");
        $this->command->info("  Afinitas: {$matched} cocok skill/minat, {$unmatched} tidak cocok");
    }

    /**
     * Feedback eksplisit, label dikaitkan dengan journey agar konsisten
     * dengan label di MlExportDataset.
     */
    private function seedFeedback(Generator $faker): void
    {
        $groups = UserInteraction::whereIn('action', ['view', 'apply', 'daftar', 'skip'])
            ->get()
            ->groupBy(fn ($i) => $i->user_id.'|'.$i->item_type.'|'.$i->item_id);

        $feedbackTarget = min($groups->count(), 250);
        if ($feedbackTarget <= 0) {
            return;
        }

        foreach ($groups->random($feedbackTarget) as $group) {
            $actions = $group->pluck('action');
            $isPos = $actions->intersect(['apply', 'daftar'])->isNotEmpty();

            UserFeedback::firstOrCreate(
                [
                    'user_id' => $group->first()->user_id,
                    'item_type' => $group->first()->item_type,
                    'item_id' => $group->first()->item_id,
                ],
                [
                    'label' => $isPos ? 1 : 0,
                    'rating' => $isPos ? $faker->numberBetween(4, 5) : $faker->numberBetween(1, 3),
                    'comment' => $faker->optional(0.3)->sentence(),
                    'event_at' => $group->first()->event_at,
                ]
            );
        }

        $this->command->info("  Feedback: {$feedbackTarget} records");
    }
}
