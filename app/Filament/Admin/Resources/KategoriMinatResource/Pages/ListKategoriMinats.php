<?php

namespace App\Filament\Admin\Resources\KategoriMinatResource\Pages;

use App\Filament\Admin\Resources\KategoriMinatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriMinats extends ListRecords
{
    protected static string $resource = KategoriMinatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
