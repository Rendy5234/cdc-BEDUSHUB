<?php

namespace App\Filament\Admin\Resources\KategoriMinatResource\Pages;

use App\Filament\Admin\Resources\KategoriMinatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriMinat extends EditRecord
{
    protected static string $resource = KategoriMinatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
