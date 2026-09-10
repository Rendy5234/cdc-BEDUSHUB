<?php

namespace App\Filament\Admin\Resources\LowonganResource\Pages;

use App\Filament\Admin\Resources\LowonganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLowongans extends ListRecords
{
    protected static string $resource = LowonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
