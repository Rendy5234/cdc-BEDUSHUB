<?php

namespace App\Filament\Siswa\Widgets;

use App\Filament\Siswa\Resources\LamaranResource;
use App\Filament\Siswa\Resources\PendaftaranPelatihanResource;
use App\Models\Lowongan;
use App\Models\Pelatihan;
use App\Models\RekomendasiLog;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RekomendasiWidget extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.siswa.widgets.rekomendasi';

    public ?Lowongan $selectedLowongan = null;

    public ?Pelatihan $selectedPelatihan = null;

    protected function getViewData(): array
    {
        return [
            'lowongan' => $this->rekomendasi('lowongan'),
            'pelatihan' => $this->rekomendasi('pelatihan'),
        ];
    }

    /**
     * @return Collection<int, array{item: Lowongan|Pelatihan, imageUrl: string|null}>
     */
    protected function rekomendasi(string $tipeItem): Collection
    {
        $logs = RekomendasiLog::query()
            ->where('user_id', auth()->id())
            ->where('tipe_item', $tipeItem)
            ->latest()
            ->limit(10)
            ->get();

        return $logs
            ->map(function (RekomendasiLog $log) use ($tipeItem): ?array {
                $item = $tipeItem === 'lowongan'
                    ? Lowongan::query()->tersediaUntukUser()->find($log->item_id)
                    : Pelatihan::query()->tersediaUntukUser()->find($log->item_id);

                if (! $item) {
                    return null;
                }

                $imageUrl = $this->imageUrl($item->thumbnail);

                if (! $imageUrl && $tipeItem === 'lowongan') {
                    $imageUrl = $this->imageUrl($item->perusahaan?->logo);
                }

                return [
                    'item' => $item,
                    'imageUrl' => $imageUrl,
                ];
            })
            ->filter()
            ->values();
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
