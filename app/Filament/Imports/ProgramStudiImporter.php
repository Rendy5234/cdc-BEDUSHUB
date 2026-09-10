<?php

namespace App\Filament\Imports;

use App\Models\Fakultas;
use App\Models\ImportSuccessfulRow;
use App\Models\ProgramStudi;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProgramStudiImporter extends Importer
{
    protected static ?string $model = ProgramStudi::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->label('Nama Program Studi')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Teknik Informatika'),
            ImportColumn::make('kode')
                ->label('Kode')
                ->rules(['nullable', 'max:255'])
                ->example('TI'),
            ImportColumn::make('jenjang')
                ->label('Jenjang')
                ->requiredMapping()
                ->rules(['required', Rule::in(['d3', 's1'])])
                ->castStateUsing(fn ($state): ?string => is_string($state) ? strtolower($state) : $state)
                ->example('s1'),
            ImportColumn::make('institusi')
                ->label('Institusi')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->fillRecordUsing(fn () => null)
                ->example('Universitas BEDUS'),
            ImportColumn::make('fakultas')
                ->label('Fakultas')
                ->relationship(resolveUsing: function (string $state, array $data): ?Fakultas {
                    $institusi = $data['institusi'] ?? null;

                    return Fakultas::query()
                        ->where('nama', $state)
                        ->when(filled($institusi), fn ($query) => $query->whereHas(
                            'institusi',
                            fn ($query) => $query->where('nama', $institusi),
                        ))
                        ->first();
                })
                ->requiredMapping()
                ->rules(['required'])
                ->example('Fakultas Teknik'),
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
                'kode' => $this->getData()['kode'] ?? null,
                'jenjang' => $this->getData()['jenjang'] ?? null,
                'institusi' => $this->getData()['institusi'] ?? null,
                'fakultas' => $this->getData()['fakultas'] ?? null,
            ],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import program studi selesai, ' . number_format($import->successful_rows)
            . ' ' . Str::plural('baris', $import->successful_rows) . ' berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount)
                . ' ' . Str::plural('baris', $failedRowsCount) . ' gagal diimpor.';
        }

        return $body;
    }
}
