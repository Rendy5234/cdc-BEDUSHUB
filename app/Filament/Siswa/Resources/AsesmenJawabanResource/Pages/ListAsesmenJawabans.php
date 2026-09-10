<?php

namespace App\Filament\Siswa\Resources\AsesmenJawabanResource\Pages;

use App\Filament\Siswa\Resources\AsesmenJawabanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsesmenJawabans extends ListRecords
{
    protected static string $resource = AsesmenJawabanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
