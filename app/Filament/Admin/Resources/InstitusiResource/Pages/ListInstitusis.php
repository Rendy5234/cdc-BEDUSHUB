<?php

namespace App\Filament\Admin\Resources\InstitusiResource\Pages;

use App\Filament\Admin\Resources\InstitusiResource;
use App\Filament\Admin\Resources\InstitusiResource\Widgets\ImportResultPoller;
use App\Filament\Imports\InstitusiImporter;
use App\Models\ImportSuccessfulRow;
use Filament\Actions;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use Filament\Resources\Pages\ListRecords;
use League\Csv\Bom;
use League\Csv\Writer;
use Livewire\Attributes\On;
use SplTempFileObject;

class ListInstitusis extends ListRecords
{
    protected static string $resource = InstitusiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('hasilImport')
                ->label('Hasil Import')
                ->icon('heroicon-m-list-bullet')
                ->color('gray')
                ->modalHeading('Hasil Import Institusi')
                ->modalWidth('4xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalContent(function () {
                    $import = Import::query()
                        ->where('importer', InstitusiImporter::class)
                        ->whereNotNull('completed_at')
                        ->latest('id')
                        ->first();

                    $successfulRows = $import
                        ? ImportSuccessfulRow::query()->where('import_id', $import->getKey())->orderBy('id')->get()
                        : collect();

                    $failedRows = $import
                        ? $import->failedRows()->orderBy('id')->get()
                        : collect();

                    return view('filament.imports.institusi-import-result', [
                        'import' => $import,
                        'successfulRows' => $successfulRows,
                        'failedRows' => $failedRows,
                    ]);
                }),
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    $columns = InstitusiImporter::getColumns();

                    $csv = Writer::createFromFileObject(new SplTempFileObject);
                    $csv->setOutputBOM(Bom::Utf8);
                    $csv->noEnclosure();

                    $csv->insertOne(array_map(
                        fn (ImportColumn $column): string => $column->getExampleHeader(),
                        $columns,
                    ));

                    $columnExamples = array_map(
                        fn (ImportColumn $column): array => $column->getExamples(),
                        $columns,
                    );

                    $exampleRowsCount = array_reduce(
                        $columnExamples,
                        fn (int $count, array $exampleData): int => max($count, count($exampleData)),
                        initial: 0,
                    );

                    $exampleRows = [];

                    foreach ($columnExamples as $exampleData) {
                        for ($i = 0; $i < $exampleRowsCount; $i++) {
                            $exampleRows[$i][] = $exampleData[$i] ?? '';
                        }
                    }

                    $csv->insertAll($exampleRows);

                    return response()->streamDownload(function () use ($csv) {
                        echo $csv->toString();
                    }, 'template-institusi.csv', [
                        'Content-Type' => 'text/csv',
                    ]);
                }),
            Actions\ImportAction::make()
                ->importer(InstitusiImporter::class)
                ->modalDescription(null),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ImportResultPoller::class,
        ];
    }

    #[On('open-import-result')]
    public function openImportResult(): void
    {
        $this->mountAction('hasilImport');
    }
}
