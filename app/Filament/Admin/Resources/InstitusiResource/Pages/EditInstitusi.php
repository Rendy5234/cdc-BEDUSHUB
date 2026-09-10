<?php

namespace App\Filament\Admin\Resources\InstitusiResource\Pages;

use App\Filament\Admin\Resources\InstitusiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstitusi extends EditRecord
{
    protected static string $resource = InstitusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
