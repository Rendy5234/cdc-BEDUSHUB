<?php

namespace App\Filament\Admin\Pages;

use App\Models\Pengaturan;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class PengaturanPage extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Sistem';

    protected static ?string $title = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $slug = 'pengaturan';

    protected static string $view = 'filament.admin.pages.pengaturan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            Pengaturan::pluck('value', 'key')->toArray(),
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_aplikasi')
                    ->label('Nama Aplikasi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('nama_instansi')
                    ->label('Nama Instansi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email_kontak')
                    ->label('Email Kontak')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('telepon')
                    ->label('Telepon')
                    ->tel()
                    ->maxLength(255),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('footer_text')
                    ->label('Teks Footer')
                    ->columnSpanFull()
                    ->maxLength(255),
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

        foreach ($data as $key => $value) {
            Pengaturan::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
