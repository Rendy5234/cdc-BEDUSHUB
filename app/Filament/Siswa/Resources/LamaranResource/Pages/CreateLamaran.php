<?php

namespace App\Filament\Siswa\Resources\LamaranResource\Pages;

use App\Filament\Siswa\Resources\LamaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLamaran extends CreateRecord
{
    protected static string $resource = LamaranResource::class;

    public function mount(): void
    {
        parent::mount();

        $lowonganId = request()->query('lowongan_id');

        if (filled($lowonganId)) {
            $this->form->fill([
                'lowongan_id' => (int) $lowonganId,
            ]);
        }
    }
}
