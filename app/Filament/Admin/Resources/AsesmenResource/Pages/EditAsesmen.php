<?php

namespace App\Filament\Admin\Resources\AsesmenResource\Pages;

use App\Filament\Admin\Resources\AsesmenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsesmen extends EditRecord
{
    protected static string $resource = AsesmenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
