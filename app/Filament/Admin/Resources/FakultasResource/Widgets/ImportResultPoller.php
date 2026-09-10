<?php

namespace App\Filament\Admin\Resources\FakultasResource\Widgets;

use App\Filament\Imports\FakultasImporter;
use Filament\Actions\Imports\Models\Import;
use Filament\Widgets\Widget;

class ImportResultPoller extends Widget
{
    protected static string $view = 'filament.widgets.import-result-poller';

    public ?int $seenImportId = null;

    public function mount(): void
    {
        $this->seenImportId = $this->latestCompletedImportId();
    }

    public function checkForNewImport(): void
    {
        $latestId = $this->latestCompletedImportId();

        if ($latestId !== null && $latestId > ($this->seenImportId ?? 0)) {
            $this->seenImportId = $latestId;

            $this->dispatch('open-import-result');
        }
    }

    protected function latestCompletedImportId(): ?int
    {
        return Import::query()
            ->where('importer', FakultasImporter::class)
            ->whereNotNull('completed_at')
            ->latest('id')
            ->value('id');
    }
}
