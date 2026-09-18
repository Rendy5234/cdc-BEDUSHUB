<?php

namespace App\Filament\Perusahaan\Resources\LamaranResource\Pages;

use App\Filament\Perusahaan\Resources\LamaranResource;
use Filament\Resources\Pages\ListRecords;

class ListLamarans extends ListRecords
{
    protected static string $resource = LamaranResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
