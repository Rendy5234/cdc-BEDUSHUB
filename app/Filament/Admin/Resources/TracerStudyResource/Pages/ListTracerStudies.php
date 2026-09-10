<?php

namespace App\Filament\Admin\Resources\TracerStudyResource\Pages;

use App\Filament\Admin\Resources\TracerStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTracerStudies extends ListRecords
{
    protected static string $resource = TracerStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
