<?php

namespace App\Filament\Admin\Resources\PendaftaranPelatihanResource\Pages;

use App\Filament\Admin\Resources\PendaftaranPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendaftaranPelatihans extends ListRecords
{
    protected static string $resource = PendaftaranPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
