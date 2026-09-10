<?php

namespace App\Filament\Siswa\Resources\LamaranResource\Pages;

use App\Filament\Siswa\Resources\LamaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLamaran extends EditRecord
{
    protected static string $resource = LamaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
