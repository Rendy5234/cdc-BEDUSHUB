<?php

namespace App\Filament\Admin\Resources\PerusahaanResource\Pages;

use App\Filament\Admin\Resources\PerusahaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerusahaan extends EditRecord
{
    protected static string $resource = PerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
