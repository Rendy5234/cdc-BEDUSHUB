<?php

namespace App\Filament\Siswa\Resources\LamaranResource\Pages;

use App\Filament\Siswa\Resources\LamaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLamarans extends ListRecords
{
    protected static string $resource = LamaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
