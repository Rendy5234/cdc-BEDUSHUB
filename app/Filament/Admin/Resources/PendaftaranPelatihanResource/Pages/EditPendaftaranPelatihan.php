<?php

namespace App\Filament\Admin\Resources\PendaftaranPelatihanResource\Pages;

use App\Filament\Admin\Resources\PendaftaranPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaranPelatihan extends EditRecord
{
    protected static string $resource = PendaftaranPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
