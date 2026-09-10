<?php

namespace App\Filament\Admin\Resources\PengaturanResource\Pages;

use App\Filament\Admin\Resources\PengaturanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaturan extends EditRecord
{
    protected static string $resource = PengaturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
