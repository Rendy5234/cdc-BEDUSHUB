<?php

namespace App\Filament\Admin\Resources\PotensiDaerahResource\Pages;

use App\Filament\Admin\Resources\PotensiDaerahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPotensiDaerahs extends ListRecords
{
    protected static string $resource = PotensiDaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
