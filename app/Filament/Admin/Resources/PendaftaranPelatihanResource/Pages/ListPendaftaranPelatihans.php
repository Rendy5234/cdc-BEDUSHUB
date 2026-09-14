<?php

namespace App\Filament\Admin\Resources\PendaftaranPelatihanResource\Pages;

use App\Filament\Admin\Resources\PendaftaranPelatihanResource;
use App\Models\PendaftaranPelatihan;
use Closure;
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

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return function (PendaftaranPelatihan $record): ?string {
            if ($record->trashed()) {
                return null;
            }

            return static::getResource()::getUrl('edit', ['record' => $record]);
        };
    }
}
