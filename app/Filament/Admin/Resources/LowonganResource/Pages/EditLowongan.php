<?php

namespace App\Filament\Admin\Resources\LowonganResource\Pages;

use App\Filament\Admin\Resources\LowonganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLowongan extends EditRecord
{
    protected static string $resource = LowonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
