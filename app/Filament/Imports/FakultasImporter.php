<?php

namespace App\Filament\Imports;

use App\Models\Fakultas;
use App\Models\ImportSuccessfulRow;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FakultasImporter extends Importer
{
    protected static ?string $model = Fakultas::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->label('Nama Fakultas')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Fakultas Teknik'),
            ImportColumn::make('institusi')
                ->label('Institusi')
                ->relationship(resolveUsing: 'nama')
                ->requiredMapping()
                ->rules(['required'])
                ->example('Universitas BEDUS'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        return app(static::getModel());
    }

    protected function afterCreate(): void
    {
        ImportSuccessfulRow::create([
            'import_id' => $this->getImport()->getKey(),
            'data' => [
                'nama' => $this->getData()['nama'] ?? null,
                'institusi' => $this->getData()['institusi'] ?? null,
            ],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import fakultas selesai, ' . number_format($import->successful_rows)
            . ' ' . Str::plural('baris', $import->successful_rows) . ' berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount)
                . ' ' . Str::plural('baris', $failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
