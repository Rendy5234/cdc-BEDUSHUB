<?php

namespace App\Filament\Admin\Resources\PotensiDaerahResource\Pages;

use App\Filament\Admin\Resources\PotensiDaerahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPotensiDaerah extends EditRecord
{
    protected static string $resource = PotensiDaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
