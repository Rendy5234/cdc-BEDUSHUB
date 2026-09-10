<?php

namespace App\Filament\Siswa\Resources\TracerStudyResource\Pages;

use App\Filament\Siswa\Resources\TracerStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTracerStudy extends EditRecord
{
    protected static string $resource = TracerStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
