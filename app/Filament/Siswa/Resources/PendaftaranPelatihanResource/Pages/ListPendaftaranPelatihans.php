<?php

namespace App\Filament\Siswa\Resources\PendaftaranPelatihanResource\Pages;

use App\Filament\Siswa\Resources\PendaftaranPelatihanResource;
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
