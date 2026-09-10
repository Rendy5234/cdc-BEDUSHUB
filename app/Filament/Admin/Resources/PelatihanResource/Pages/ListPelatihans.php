<?php

namespace App\Filament\Admin\Resources\PelatihanResource\Pages;

use App\Filament\Admin\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelatihans extends ListRecords
{
    protected static string $resource = PelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
