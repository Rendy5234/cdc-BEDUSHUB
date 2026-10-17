<?php

namespace App\Filament\Siswa\Widgets;

use App\Filament\Siswa\Resources\LamaranResource;
use App\Filament\Siswa\Resources\PendaftaranPelatihanResource;
use App\Models\Lowongan;
use App\Models\Pelatihan;
use App\Models\RekomendasiLog;
use App\Models\User;
use App\Models\UserInteraction;
use App\Services\ML\MLRecommendationService;
use App\Services\RekomendasiService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RekomendasiWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** Jumlah kartu rekomendasi per tipe item. */
    private const LIMIT = 5;

    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.siswa.widgets.rekomendasi';

    public ?Lowongan $selectedLowongan = null;

    public ?Pelatihan $selectedPelatihan = null;

    protected MLRecommendationService $mlService;

    protected RekomendasiService $rekomendasiService;

    public function boot(): void
    {
        $this->mlService = app(MLRecommendationService::class);
        $this->rekomendasiService = app(RekomendasiService::class);
    }

    protected function getViewData(): array
    {
        $userId = (int) auth()->id();

        return [
            'lowongan' => $this->rekomendasiUntuk($userId, 'lowongan'),
            'pelatihan' => $this->rekomendasiUntuk($userId, 'pelatihan'),
        ];
    }

    /**
     * Generate-on-read: susun rekomendasi saat widget dirender.
     *
     * Alur:
     * 1. Bila user punya interaksi, minta hasil ALS (ML) lalu validasi item
     *    terhadap katalog yang masih tersedia.
     * 2. Bila hasil ML kurang dari target (atau user belum punya interaksi),
     *    lengkapi dengan rule-based.
     * 3. Catat ke rekomendasi_log dengan guard 1 log per item per hari, tanpa
     *    menghapus log lama (log lama tetap jadi riwayat era "Rule").
     *
     * @return Collection<int, array{item: Lowongan|Pelatihan, imageUrl: string|null, source: string, skor: float, rank: int}>
     */
    protected function rekomendasiUntuk(int $userId, string $tipeItem): Collection
    {
        $user = User::find($userId);

        if (! $user) {
            return collect();
        }

        $hasil = collect();
        $modelVersion = null;

        if ($this->mlService->isAvailable() && $this->punyaInteraksi($userId)) {
            $hasil = $this->dariModel($userId, $tipeItem);
            $modelVersion = $this->mlService->modelVersion();
        }

        $kurang = self::LIMIT - $hasil->count();

        if ($kurang > 0) {
            $hasil = $hasil->concat(
                $this->dariAturan($user, $tipeItem, $hasil->pluck('item')->pluck('id')->all(), $kurang),
            );
        }

        return $hasil->take(self::LIMIT)
            ->values()
            ->map(function (array $rec) use ($user, $tipeItem): array {
                $this->catatLog($user, $tipeItem, $rec);

                return [
                    'item' => $rec['item'],
                    'imageUrl' => $this->gambarItem($rec['item'], $tipeItem),
                    'source' => $rec['source'],
                    'skor' => $rec['skor'],
                    'rank' => $rec['rank'],
                ];
            });
    }

    /**
     * Hasil dari model ALS, hanya item yang masih tersedia di katalog.
     *
     * @return Collection<int, array{item: Lowongan|Pelatihan, source: string, skor: float, rank: int}>
     */
    protected function dariModel(int $userId, string $tipeItem): Collection
    {
        $saran = $this->mlService->recommend($userId, $tipeItem, self::LIMIT * 3);

        if ($saran === []) {
            return collect();
        }

        return collect($saran)
            ->sortBy('rank')
            ->map(function (array $baris) use ($tipeItem): ?array {
                $item = $this->cariItem($tipeItem, (int) $baris['item_id']);

                if (! $item) {
                    return null;
                }

                return [
                    'item' => $item,
                    'source' => 'ml',
                    'skor' => (float) $baris['score'],
                    'rank' => (int) $baris['rank'],
                ];
            })
            ->filter()
            ->unique(fn (array $rec) => $rec['item']->getKey())
            ->values();
    }

    /**
     * Pelengkap rule-based (tanpa item yang sudah dipilih model).
     *
     * @param  array<int, int>  $excludeIds
     * @return Collection<int, array{item: Lowongan|Pelatihan, source: string, skor: float, rank: int}>
     */
    protected function dariAturan(User $user, string $tipeItem, array $excludeIds, int $limit): Collection
    {
        $saran = $this->rekomendasiService->ruleTopUp($user, $tipeItem, $excludeIds, $limit * 2);

        return collect($saran)
            ->map(function (array $baris) use ($tipeItem): ?array {
                $item = $this->cariItem($tipeItem, (int) $baris['item_id']);

                if (! $item) {
                    return null;
                }

                return [
                    'item' => $item,
                    'source' => 'rule',
                    'skor' => (float) $baris['skor'],
                    'rank' => 0,
                ];
            })
            ->filter()
            ->unique(fn (array $rec) => $rec['item']->getKey())
            ->take($limit)
            ->values()
            ->values()
            ->map(function (array $rec, int $index): array {
                $rec['rank'] = $index + 1;

                return $rec;
            });
    }

    /**
     * Ambil item yang memang boleh direkomendasikan untuk user.
     *
     * Lowongan: memakai aturan pendaftaran (tanggal_mulai = buka pendaftaran).
     * Pelatihan: tanggal_mulai = acara dimulai, jadi yang bisa didaftar adalah
     * pelatihan yang acaranya belum dimulai.
     */
    protected function cariItem(string $tipeItem, int $id): Lowongan|Pelatihan|null
    {
        return match ($tipeItem) {
            'lowongan' => Lowongan::query()->tersediaUntukUser()->find($id),
            'pelatihan' => Pelatihan::query()->tersediaUntukRekomendasi()->find($id),
            default => null,
        };
    }

    /**
     * Simpan ke rekomendasi_log bila belum tercatat hari ini untuk item yang sama.
     *
     * @param  array{item: Lowongan|Pelatihan, source: string, skor: float}  $rec
     */
    protected function catatLog(User $user, string $tipeItem, array $rec): void
    {
        $modelVersion = $rec['source'] === 'ml' ? $this->mlService->modelVersion() : null;
        $itemId = (int) $rec['item']->getKey();

        if ($this->rekomendasiService->alreadyLoggedToday($user, $tipeItem, $itemId, $modelVersion)) {
            return;
        }

        $this->rekomendasiService->storeRecommendation(
            $user,
            $tipeItem,
            $itemId,
            round((float) $rec['skor'], 4),
            $rec['source'] === 'ml'
                ? 'Hasil model ALS berdasarkan pola perilaku Anda.'
                : 'Kecocokan skill dengan profil Anda.',
            'view',
            $modelVersion,
            $this->nomorUrutHarian($user, $tipeItem, $modelVersion),
        );
    }

    /**
     * Nomor urut (position) berikutnya pada hari ini untuk kombinasi user+tipe+versi.
     */
    protected function nomorUrutHarian(User $user, string $tipeItem, ?string $modelVersion): int
    {
        $terakhir = RekomendasiLog::query()
            ->where('user_id', $user->id)
            ->where('tipe_item', $tipeItem)
            ->when(
                $modelVersion === null,
                fn ($q) => $q->whereNull('model_version'),
                fn ($q) => $q->where('model_version', $modelVersion),
            )
            ->whereDate('created_at', now()->toDateString())
            ->max('position');

        return ((int) $terakhir) + 1;
    }

    protected function punyaInteraksi(int $userId): bool
    {
        return Cache::rememberForever(
            "user_interaction_exists_{$userId}",
            fn (): bool => UserInteraction::query()->where('user_id', $userId)->exists(),
        );
    }

    protected function gambarItem(Lowongan|Pelatihan $item, string $tipeItem): ?string
    {
        $imageUrl = $this->imageUrl($item->thumbnail);

        if (! $imageUrl && $tipeItem === 'lowongan' && $item instanceof Lowongan) {
            $imageUrl = $this->imageUrl($item->perusahaan?->logo);
        }

        return $imageUrl;
    }

    public function openLowonganDetail(int $id): void
    {
        $this->selectedLowongan = Lowongan::query()->tersediaUntukUser()->find($id);

        if ($this->selectedLowongan) {
            $this->mountAction('detailLowongan');
        }
    }

    public function openPelatihanDetail(int $id): void
    {
        $this->selectedPelatihan = Pelatihan::query()->tersediaUntukUser()->find($id);

        if ($this->selectedPelatihan) {
            $this->mountAction('detailPelatihan');
        }
    }

    public function imageUrl(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }

    public function detailLowonganAction(): Action
    {
        return Action::make('detailLowongan')
            ->modalHeading(fn (): string => $this->selectedLowongan?->judul ?? 'Detail Lowongan')
            ->modalContent(fn () => view('filament.siswa.widgets.detail-lowongan', [
                'lowongan' => $this->selectedLowongan,
                'imageUrl' => $this->imageUrl($this->selectedLowongan?->thumbnail)
                    ?? $this->imageUrl($this->selectedLowongan?->perusahaan?->logo),
            ]))
            ->modalFooterActions([
                Action::make('daftarLowongan')
                    ->label('Daftar Sekarang')
                    ->color('primary')
                    ->url(fn () => $this->selectedLowongan
                        ? LamaranResource::getUrl('create', ['lowongan_id' => $this->selectedLowongan->id])
                        : null),
                Action::make('tutupLowongan')
                    ->label('Tutup')
                    ->color('gray')
                    ->cancelParentActions(),
            ])
            ->modalSubmitAction(false);
    }

    public function detailPelatihanAction(): Action
    {
        return Action::make('detailPelatihan')
            ->modalHeading(fn (): string => $this->selectedPelatihan?->judul ?? 'Detail Pelatihan')
            ->modalContent(fn () => view('filament.siswa.widgets.detail-pelatihan', [
                'pelatihan' => $this->selectedPelatihan,
                'imageUrl' => $this->imageUrl($this->selectedPelatihan?->thumbnail),
            ]))
            ->modalFooterActions([
                Action::make('daftarPelatihan')
                    ->label('Daftar Sekarang')
                    ->color('primary')
                    ->url(fn () => $this->selectedPelatihan
                        ? PendaftaranPelatihanResource::getUrl('create', ['pelatihan_id' => $this->selectedPelatihan->id])
                        : null),
                Action::make('tutupPelatihan')
                    ->label('Tutup')
                    ->color('gray')
                    ->cancelParentActions(),
            ])
            ->modalSubmitAction(false);
    }
}
