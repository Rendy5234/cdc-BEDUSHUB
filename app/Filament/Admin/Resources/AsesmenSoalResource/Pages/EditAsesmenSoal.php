<?php

namespace App\Filament\Admin\Resources\AsesmenSoalResource\Pages;

use App\Filament\Admin\Resources\AsesmenSoalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsesmenSoal extends EditRecord
{
    protected static string $resource = AsesmenSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
