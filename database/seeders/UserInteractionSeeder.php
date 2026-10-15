<?php

namespace Database\Seeders;

use App\Models\Lowongan;
use App\Models\Pelatihan;
use App\Models\User;
use App\Models\UserFeedback;
use App\Models\UserInteraction;
use Illuminate\Database\Seeder;

class UserInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('role', ['siswa', 'mahasiswa', 'alumni'])->get();
        $lowongans = Lowongan::where('status', 'aktif')->get();
        $pelatihan = Pelatihan::where('status', 'published')->get();
        $actions = ['view', 'click', 'apply', 'daftar', 'skip', 'save'];
        $sources = ['rekomendasi', 'search', 'browse', 'external'];

        $faker = \Faker\Factory::create('id_ID');
        $count = 0;
        $target = 700;

        if ($users->isEmpty() || $lowongans->isEmpty() || $pelatihan->isEmpty()) {
            $this->command->warn('Data prerequisite kosong (users/lowongan/pelatihan). Skip seeder.');
            return;
        }

        // Buat bobot popularitas agar distribusi tidak uniform
        // (beberapa item populer, beberapa jarang)
        $lowonganPool = collect();
        foreach ($lowongans as $l) {
            $weight = rand(1, 5); // popularitas 1-5x
            for ($w = 0; $w < $weight; $w++) {
                $lowonganPool->push($l);
            }
        }
        $pelatihanPool = collect();
        foreach ($pelatihan as $p) {
            $weight = rand(1, 5);
            for ($w = 0; $w < $weight; $w++) {
                $pelatihanPool->push($p);
            }
        }

        while ($count < $target) {
            $user = $users->random();
            $isLowongan = $faker->boolean(60);
            $item = $isLowongan ? $lowonganPool->random() : $pelatihanPool->random();

            $sessionId = 'sess_' . $faker->uuid();
            $source    = $faker->randomElement($sources);

            // Simulasi journey: view → (click) → (apply/daftar/skip/save)
            // Positive bias 65%: apply/daftar lebih sering
            $journey = ['view'];
            if ($faker->boolean(75)) $journey[] = 'click';
            if ($faker->boolean(38)) $journey[] = $isLowongan ? 'apply' : 'daftar'; // lebih sering
            if ($faker->boolean(15)) $journey[] = 'save';
            if ($faker->boolean(18)) $journey[] = 'skip';

            foreach ($journey as $idx => $action) {
                UserInteraction::create([
                    'user_id'    => $user->id,
                    'item_type'  => $isLowongan ? 'lowongan' : 'pelatihan',
                    'item_id'    => $item->id,
                    'action'     => $action,
                    'session_id' => $sessionId,
                    'source'     => $source,
                    'metadata'   => ['rank' => $idx + 1, 'model' => 'baseline_v1'],
                    'event_at'   => $faker->dateTimeBetween('-60 days', 'now'),
                ]);
                $count++;
                if ($count >= $target) break 2;
            }
        }

        // Feedback eksplisit: target 130 records
        $uniqueInteractions = UserInteraction::whereIn('action', ['apply', 'daftar', 'view'])
            ->get()
            ->unique(fn ($i) => $i->user_id . '|' . $i->item_type . '|' . $i->item_id);

        $feedbackTarget = min($uniqueInteractions->count(), 130);
        if ($feedbackTarget > 0) {
            $interactions = $uniqueInteractions->random($feedbackTarget);
            foreach ($interactions as $interaction) {
                // Positive jika aksi apply/daftar, negative jika view only (30% chance)
                $isPos = in_array($interaction->action, ['apply', 'daftar']);
                UserFeedback::firstOrCreate(
                    [
                        'user_id'   => $interaction->user_id,
                        'item_type' => $interaction->item_type,
                        'item_id'   => $interaction->item_id,
                    ],
                    [
                        'label'    => $isPos ? 1 : ($faker->boolean(65) ? 1 : 0),
                        'rating'   => $isPos ? $faker->numberBetween(4, 5) : $faker->numberBetween(1, 4),
                        'comment'  => $faker->optional(0.3)->sentence(),
                        'event_at' => $interaction->event_at,
                    ]
                );
            }
        }

        $this->command->info("Seeded {$count} interactions + {$feedbackTarget} feedback.");
    }
}
