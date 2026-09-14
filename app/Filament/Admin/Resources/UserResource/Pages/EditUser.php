<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('Konfirmasi hapus')
                ->modalDescription('Masukkan password akun Anda untuk menghapus data pengguna ini.')
                ->modalSubmitActionLabel('Hapus')
                ->form([UserResource::passwordField()]),
            Actions\ForceDeleteAction::make()
                ->modalHeading('Konfirmasi hapus permanen')
                ->modalDescription('Masukkan password akun Anda untuk menghapus permanen data pengguna ini.')
                ->modalSubmitActionLabel('Hapus permanen')
                ->form([UserResource::passwordField()]),
            Actions\RestoreAction::make(),
        ];
    }
}
