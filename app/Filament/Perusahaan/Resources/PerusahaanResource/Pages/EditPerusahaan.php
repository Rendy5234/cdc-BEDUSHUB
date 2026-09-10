<?php

namespace App\Filament\Perusahaan\Resources\PerusahaanResource\Pages;

use App\Filament\Perusahaan\Resources\PerusahaanResource;
use Filament\Resources\Pages\EditRecord;

class EditPerusahaan extends EditRecord
{
    protected static string $resource = PerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
