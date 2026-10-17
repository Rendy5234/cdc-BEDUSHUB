<?php

namespace App\Services\ML;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Jembatan PHP → model ALS (collaborative filtering) di folder `ml/`.
 *
 * Arsitektur prototipe: training offline via Python, inference dipanggil dari
 * PHP lewat `predict.py` (stdout JSON). Tidak ada web service Python.
 *
 * Catatan penting tentang `shell_exec`:
 * - TIDAK memakai `2>&1`. Peringatan OpenBLAS dari library numpy ditulis ke
 *   stderr; bila digabung, JSON di stdout menjadi rusak saat di-decode.
 * - Ekstraksi JSON dibuat defensif (regex) untuk berjaga-jaga bila ada noise
 *   lain yang lolos ke stdout.
 */
class MLRecommendationService
{
    /** Versi model yang dicatat di `rekomendasi_log.model_version`. */
    public const MODEL_VERSION = 'als_v2';

    /** Lama cache hasil inference (detik). */
    private const CACHE_TTL = 3600;

    /**
     * Ambil rekomendasi dari model ALS.
     *
     * @return array<int, array{item_id: int, score: float, rank: int}>
     */
    public function recommend(int $userId, string $itemType, int $topK = 5): array
    {
        if (! $this->isAvailable()) {
            Log::warning('ML recommend dilewati: artefak model atau interpreter Python tidak tersedia.');

            return [];
        }

        $cacheKey = "ml_rec_{$userId}_{$itemType}_{$topK}";

        return Cache::remember(
            $cacheKey,
            self::CACHE_TTL,
            fn (): array => $this->jalankanPython($userId, $itemType, $topK),
        );
    }

    /**
     * Putuskan cache untuk satu user+tipe item, misal setelah ada interaksi baru.
     */
    public function forget(int $userId, ?string $itemType = null): void
    {
        if ($itemType !== null) {
            foreach ([5, 10, 20] as $topK) {
                Cache::forget("ml_rec_{$userId}_{$itemType}_{$topK}");
            }

            return;
        }

        foreach (['lowongan', 'pelatihan'] as $tipe) {
            $this->forget($userId, $tipe);
        }
    }

    public function isAvailable(): bool
    {
        return is_file($this->pythonBinary())
            && is_file($this->scriptPath())
            && is_file(base_path('ml/model/model.npz'))
            && is_file(base_path('ml/model/mappings.pkl'));
    }

    public function modelVersion(): string
    {
        return self::MODEL_VERSION;
    }

    public function pythonBinary(): string
    {
        return base_path('ml/venv/Scripts/python.exe');
    }

    public function scriptPath(): string
    {
        return base_path('ml/predict.py');
    }

    /**
     * @return array<int, array{item_id: int, score: float, rank: int}>
     */
    private function jalankanPython(int $userId, string $itemType, int $topK): array
    {
        $command = sprintf(
            '%s %s --user_id=%d --item_type=%s --top_k=%d',
            escapeshellarg($this->pythonBinary()),
            escapeshellarg($this->scriptPath()),
            $userId,
            escapeshellarg($itemType),
            $topK,
        );

        // Batasi thread OpenBLAS: library implicit/numpy memberi warning bila pakai
        // threadpool, dan di Windows dapat menyebabkan crash process (segfault).
        if (PHP_OS_FAMILY === 'Windows') {
            putenv('OPENBLAS_NUM_THREADS=1');
        }

        $output = @shell_exec($command);

        if (! is_string($output) || trim($output) === '') {
            Log::warning('ML recommend: output Python kosong.', [
                'user_id' => $userId,
                'item_type' => $itemType,
            ]);

            return [];
        }

        $json = $this->ekstrakJson($output);

        if ($json === null) {
            Log::warning('ML recommend: JSON tidak ditemukan pada output Python.', [
                'user_id' => $userId,
                'item_type' => $itemType,
                'output' => mb_substr(trim($output), 0, 500),
            ]);

            return [];
        }

        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            Log::warning('ML recommend: JSON gagal di-decode.', [
                'user_id' => $userId,
                'item_type' => $itemType,
            ]);

            return [];
        }

        return $this->normalisasi($decoded);
    }

    /**
     * Ambil potongan JSON array pertama dari output, abaikan noise apa pun di
     * sekitarnya (mis. stack trace atau peringatan library).
     */
    private function ekstrakJson(string $output): ?string
    {
        if (preg_match('/\[\s*(?:\{.*?\}\s*,?\s*)*\]/s', $output, $matches)) {
            return $matches[0];
        }

        return null;
    }

    /**
     * @param  array<mixed>  $decoded
     * @return array<int, array{item_id: int, score: float, rank: int}>
     */
    private function normalisasi(array $decoded): array
    {
        $hasil = [];

        foreach ($decoded as $baris) {
            if (! is_array($baris) || ! isset($baris['item_id'])) {
                continue;
            }

            $hasil[] = [
                'item_id' => (int) $baris['item_id'],
                'score' => (float) ($baris['score'] ?? 0.0),
                'rank' => (int) ($baris['rank'] ?? count($hasil) + 1),
            ];
        }

        usort($hasil, fn (array $a, array $b): int => $a['rank'] <=> $b['rank']);

        return $hasil;
    }
}
