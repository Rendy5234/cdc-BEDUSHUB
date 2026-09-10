<?php

namespace App\Filament\Admin\Resources\PengaturanResource\Pages;

use App\Filament\Admin\Resources\PengaturanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengaturans extends ListRecords
{
    protected static string $resource = PengaturanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
