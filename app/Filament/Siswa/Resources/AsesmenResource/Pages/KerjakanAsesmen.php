<?php

namespace App\Filament\Siswa\Resources\AsesmenResource\Pages;

use App\Filament\Siswa\Resources\AsesmenResource;
use App\Models\AsesmenJawaban;
use App\Models\AsesmenSoal;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class KerjakanAsesmen extends Page
{
    use InteractsWithFormActions;
    use InteractsWithRecord;

    protected static string $resource = AsesmenResource::class;

    protected static string $view = 'filament.siswa.pages.kerjakan-asesmen';

    public ?array $data = [];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->load('asesmenSoals');

        $jawabanLama = AsesmenJawaban::query()
            ->where('asesmen_id', $this->record->id)
            ->where('user_id', auth()->id())
            ->get()
            ->keyBy('soal_id');

        $this->form->fill(
            $this->record->asesmenSoals
                ->mapWithKeys(fn (AsesmenSoal $soal) => [
                    $this->fieldName($soal) => $jawabanLama->get($soal->id)?->jawaban,
                ])
                ->all(),
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                $this->record->asesmenSoals
                    ->map(fn (AsesmenSoal $soal) => $this->fieldFor($soal))
                    ->all(),
            )
            ->statePath('data');
    }

    public function getTitle(): string
    {
        return $this->record?->judul ?? 'Kerjakan Asesmen';
    }

    public function getHeading(): string
    {
        return $this->record?->judul ?? 'Kerjakan Asesmen';
    }

    public function getSubheading(): ?string
    {
        return $this->record?->deskripsi;
    }

    protected function fieldName(AsesmenSoal $soal): string
    {
        return 'jawaban_'.$soal->id;
    }

    protected function fieldFor(AsesmenSoal $soal): Component
    {
        $name = $this->fieldName($soal);

        if ($soal->tipe_jawaban === 'teks') {
            return Textarea::make($name)
                ->label($soal->pertanyaan)
                ->required()
                ->columnSpanFull();
        }

        $opsi = collect($soal->opsi ?? [])
            ->values()
            ->mapWithKeys(fn (string $o) => [$o => $o])
            ->all();

        return Radio::make($name)
            ->label($soal->pertanyaan)
            ->options($opsi)
            ->required();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($this->record->asesmenSoals as $soal) {
            AsesmenJawaban::updateOrCreate(
                [
                    'asesmen_id' => $this->record->id,
                    'soal_id' => $soal->id,
                    'user_id' => auth()->id(),
                ],
                [
                    'jawaban' => $data[$this->fieldName($soal)] ?? null,
                ],
            );
        }

        Notification::make()
            ->title('Jawaban asesmen berhasil disimpan')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Jawaban')
                ->submit('save'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali')
                ->label('Kembali')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(AsesmenResource::getUrl('index')),
        ];
    }
}
