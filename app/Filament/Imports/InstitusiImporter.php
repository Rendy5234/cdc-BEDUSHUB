<?php

namespace App\Filament\Imports;

use App\Models\ImportSuccessfulRow;
use App\Models\Institusi;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InstitusiImporter extends Importer
{
    protected static ?string $model = Institusi::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->label('Nama Institusi')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Universitas BEDUS'),
            ImportColumn::make('jenis')
                ->label('Jenis')
                ->requiredMapping()
                ->rules(['required', Rule::in(['smk', 'sma', 'pt'])])
                ->castStateUsing(fn ($state): ?string => is_string($state) ? strtolower($state) : $state)
                ->example('pt'),
            ImportColumn::make('kecamatan')
                ->label('Kecamatan')
                ->rules(['nullable', 'max:255'])
                ->example('Bedus'),
            ImportColumn::make('status')
                ->label('Status')
                ->rules(['nullable', Rule::in(['aktif', 'nonaktif'])])
                ->castStateUsing(fn ($state): ?string => is_string($state) ? strtolower($state) : 'aktif')
                ->example('aktif'),
            ImportColumn::make('alamat')
                ->label('Alamat')
                ->rules(['nullable'])
                ->example('Jl. Pendidikan No. 1'),
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
                'jenis' => $this->getData()['jenis'] ?? null,
                'kecamatan' => $this->getData()['kecamatan'] ?? null,
                'status' => $this->getData()['status'] ?? null,
                'alamat' => $this->getData()['alamat'] ?? null,
            ],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import institusi selesai, ' . number_format($import->successful_rows)
            . ' ' . Str::plural('baris', $import->successful_rows) . ' berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount)
                . ' ' . Str::plural('baris', $failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
