<?php

namespace App\Filament\Admin\Resources\AsesmenJawabanResource\Pages;

use App\Filament\Admin\Resources\AsesmenJawabanResource;
use Filament\Resources\Pages\ListRecords;

class ListAsesmenJawabans extends ListRecords
{
    protected static string $resource = AsesmenJawabanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
