<?php

namespace App\Filament\Admin\Resources\SektorUnggulanResource\Pages;

use App\Filament\Admin\Resources\SektorUnggulanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSektorUnggulans extends ListRecords
{
    protected static string $resource = SektorUnggulanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
