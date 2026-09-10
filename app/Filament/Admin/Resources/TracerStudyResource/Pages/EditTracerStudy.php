<?php

namespace App\Filament\Admin\Resources\TracerStudyResource\Pages;

use App\Filament\Admin\Resources\TracerStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTracerStudy extends EditRecord
{
    protected static string $resource = TracerStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
