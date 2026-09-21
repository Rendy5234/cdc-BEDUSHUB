<?php

namespace App\Filament\Siswa\Resources\PendaftaranPelatihanResource\Pages;

use App\Filament\Siswa\Resources\PendaftaranPelatihanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePendaftaranPelatihan extends CreateRecord
{
    protected static string $resource = PendaftaranPelatihanResource::class;

    public function mount(): void
    {
        parent::mount();

        $pelatihanId = request()->query('pelatihan_id');

        if (filled($pelatihanId)) {
            $this->form->fill([
                'pelatihan_id' => (int) $pelatihanId,
            ]);
        }
    }
}
