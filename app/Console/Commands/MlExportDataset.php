<?php

namespace App\Console\Commands;

use App\Models\Lowongan;
use App\Models\Pelatihan;
use App\Models\User;
use App\Models\UserFeedback;
use App\Models\UserInteraction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MlExportDataset extends Command
{
    protected $signature = 'ml:export-dataset {--path=ml/training_data} {--limit=0}';
    protected $description = 'Export 3 CSV untuk training: interactions.csv, user_features.csv, item_features.csv';

    public function handle()
    {
        $path = base_path($this->option('path'));
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        $limit = (int) $this->option('limit');

        $this->info("Exporting to {$path}...");

        // 1. INTERACTIONS → Training samples (positive/negative labels)
        $this->exportInteractions($path, $limit);

        // 2. USER FEATURES
        $this->exportUserFeatures($path, $limit);

        // 3. ITEM FEATURES (lowongan + pelatihan)
        $this->exportItemFeatures($path, $limit);

        $this->info('Done. 3 CSV files ready for Python.');
    }

    private function exportInteractions(string $path, int $limit)
    {
        $query = UserInteraction::with('user');
        if ($limit > 0) {
            $query->limit($limit);
        }
        
        $interactions = $query->get()
            ->groupBy(fn($i) => "{$i->user_id}-{$i->item_type}-{$i->item_id}")
            ->map(function ($group) {
                $actions = $group->pluck('action')->unique();
                $hasPositiveAction = $actions->intersect(['apply', 'daftar'])->isNotEmpty();
                $hasNegativeFeedback = UserFeedback::where('user_id', $group[0]->user_id)
                    ->where('item_type', $group[0]->item_type)
                    ->where('item_id', $group[0]->item_id)
                    ->whereIn('label', [0, -1])
                    ->exists();

                $label = $hasPositiveAction ? 1 : ($hasNegativeFeedback || $actions->contains('view') ? 0 : null);

                if ($label === null) return null;

                $first = $group->first();
                return [
                    'user_id' => $first->user_id,
                    'item_type' => $first->item_type,
                    'item_id' => $first->item_id,
                    'label' => $label,
                    'primary_action' => $actions->first(),
                    'all_actions' => $actions->implode(','),
                    'session_id' => $first->session_id,
                    'source' => $first->source,
                    'event_at' => $first->event_at?->toISOString(),
                ];
            })
            ->filter()
            ->values();

        $this->exportCsv($path . '/interactions.csv', $interactions->toArray());
        $this->line("  interactions.csv: {$interactions->count()} rows (pos: {$interactions->where('label',1)->count()}, neg: {$interactions->where('label',0)->count()})");
    }

    private function exportUserFeatures(string $path, int $limit)
    {
        $userIds = UserInteraction::distinct('user_id')->pluck('user_id');
        if ($limit > 0) {
            $userIds = $userIds->take($limit);
        }

        $features = User::whereIn('id', $userIds)
            ->with(['profile', 'userSkills', 'minat', 'asesmenJawabans'])
            ->get()
            ->map(function ($u) {
                $profile = $u->profile;
                $skills = $u->userSkills->pluck('skill_id')->toArray();
                $minat = $u->minat->pluck('kategori_minat_id')->toArray();
                $asesmenScores = $u->asesmenJawabans->pluck('skor')->filter()->toArray();

                return [
                    'user_id' => $u->id,
                    'jenjang' => $profile->jenjang ?? 'unknown',
                    'domisili' => $profile->domisili ?? 'unknown',
                    'fakultas_id' => $profile->fakultas_id,
                    'prodi_id' => $profile->prodi_id,
                    'skill_ids' => implode(',', $skills),
                    'skill_count' => count($skills),
                    'minat_ids' => implode(',', $minat),
                    'minat_count' => count($minat),
                    'asesmen_avg' => $asesmenScores ? array_sum($asesmenScores)/count($asesmenScores) : 0,
                    'asesmen_count' => count($asesmenScores),
                ];
            });

        $this->exportCsv($path . '/user_features.csv', $features->toArray());
        $this->line("  user_features.csv: {$features->count()} rows");
    }

    private function exportItemFeatures(string $path, int $limit)
    {
        $itemIds = UserInteraction::distinct('item_type', 'item_id')->get(['item_type', 'item_id']);
        if ($limit > 0) {
            $itemIds = $itemIds->take($limit);
        }

        $rows = collect();

        foreach ($itemIds as $item) {
            if ($item->item_type === 'lowongan') {
                $lowongan = Lowongan::with(['skills', 'minat'])->find($item->item_id);
                if (!$lowongan) continue;

                $rows->push([
                    'item_type' => 'lowongan',
                    'item_id' => $lowongan->id,
                    'judul' => $lowongan->judul,
                    'skill_ids' => $lowongan->skills->pluck('id')->implode(','),
                    'minat_ids' => $lowongan->minat->pluck('id')->implode(','),
                    'lokasi' => $lowongan->lokasi,
                    'gaji_min' => $lowongan->gaji_min,
                    'gaji_max' => $lowongan->gaji_max,
                    'pendidikan_min' => is_array($lowongan->pendidikan) ? implode(',', $lowongan->pendidikan) : $lowongan->pendidikan,
                    'tipe_pekerjaan' => $lowongan->tipe_pekerjaan,
                    'status' => $lowongan->status,
                ]);
            } elseif ($item->item_type === 'pelatihan') {
                $pelatihan = Pelatihan::with(['skills', 'minat'])->find($item->item_id);
                if (!$pelatihan) continue;

                $rows->push([
                    'item_type' => 'pelatihan',
                    'item_id' => $pelatihan->id,
                    'judul' => $pelatihan->judul,
                    'skill_ids' => $pelatihan->skills->pluck('id')->implode(','),
                    'minat_ids' => $pelatihan->minat->pluck('id')->implode(','),
                    'jenis' => $pelatihan->jenis,
                    'level' => $pelatihan->level,
                    'tempat' => $pelatihan->tempat,
                    'instruktur' => $pelatihan->instruktur,
                    'status' => $pelatihan->status,
                ]);
            }
        }

        $this->exportCsv($path . '/item_features.csv', $rows->toArray());
        $this->line("  item_features.csv: {$rows->count()} rows");
    }

    private function exportCsv(string $file, array $data)
    {
        if (empty($data)) {
            $this->warn("Empty: {$file}");
            return;
        }
        $handle = fopen($file, 'w');
        fputcsv($handle, array_keys($data[0]));
        foreach ($data as $row) {
            fputcsv($handle, array_values($row));
        }
        fclose($handle);
    }
}
