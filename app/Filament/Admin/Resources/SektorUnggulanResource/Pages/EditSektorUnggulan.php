<?php

namespace App\Filament\Admin\Resources\SektorUnggulanResource\Pages;

use App\Filament\Admin\Resources\SektorUnggulanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSektorUnggulan extends EditRecord
{
    protected static string $resource = SektorUnggulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
