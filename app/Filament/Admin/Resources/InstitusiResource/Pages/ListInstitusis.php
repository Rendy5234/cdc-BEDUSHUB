<?php

namespace App\Filament\Admin\Resources\InstitusiResource\Pages;

use App\Filament\Admin\Resources\InstitusiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstitusis extends ListRecords
{
    protected static string $resource = InstitusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
