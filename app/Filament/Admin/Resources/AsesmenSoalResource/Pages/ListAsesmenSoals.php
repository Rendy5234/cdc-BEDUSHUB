<?php

namespace App\Filament\Admin\Resources\AsesmenSoalResource\Pages;

use App\Filament\Admin\Resources\AsesmenSoalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsesmenSoals extends ListRecords
{
    protected static string $resource = AsesmenSoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
