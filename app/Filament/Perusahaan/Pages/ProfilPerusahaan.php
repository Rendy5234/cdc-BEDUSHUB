<?php

namespace App\Filament\Perusahaan\Pages;

use App\Models\Perusahaan;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class ProfilPerusahaan extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Profil';

    protected static ?string $title = 'Profil Perusahaan';

    protected static ?string $navigationLabel = 'Profil Perusahaan';

    protected static ?string $slug = 'profil-perusahaan';

    protected static string $view = 'filament.perusahaan.pages.profil-perusahaan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            auth()->user()->perusahaan?->only([
                'nama',
                'bidang_usaha',
                'kecamatan',
                'no_telp',
                'status_kerjasama',
                'logo',
                'alamat',
                'deskripsi',
            ]) ?? [],
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('bidang_usaha')
                    ->maxLength(255),
                TextInput::make('kecamatan')
                    ->maxLength(255),
                TextInput::make('no_telp')
                    ->tel()
                    ->maxLength(255),
                Select::make('status_kerjasama')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ])
                    ->default('aktif'),
                FileUpload::make('logo')
                    ->image()
                    ->directory('perusahaan'),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                Textarea::make('deskripsi')
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Perusahaan::updateOrCreate(
            ['user_id' => auth()->id()],
            $data,
        );

        Notification::make()
            ->title('Profil perusahaan berhasil disimpan')
            ->success()
            ->send();
    }
}
