<?php

namespace App\Filament\Perusahaan\Resources;

use App\Filament\Perusahaan\Resources\PelamarResource\Pages;
use App\Models\Lamaran;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PelamarResource extends Resource
{
    protected static ?string $model = Lamaran::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'pelamar';

    protected static ?string $modelLabel = 'Detail Pendaftar';

    protected static ?string $pluralModelLabel = 'Detail Pendaftar';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('lowongan.perusahaan', fn (Builder $query) => $query->where('user_id', auth()->id()));
    }

    public static function getRecordTitle(?Model $record): string
    {
        return $record?->user?->name ?? 'Detail Pendaftar';
    }

    public static function getPages(): array
    {
        return [
            'view' => Pages\ViewPelamar::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
