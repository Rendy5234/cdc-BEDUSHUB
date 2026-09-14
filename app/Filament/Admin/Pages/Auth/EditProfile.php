<?php

namespace App\Filament\Admin\Pages\Auth;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('current_password')
                    ->label('Password Lama')
                    ->password()
                    ->revealable()
                    ->autocomplete('current-password')
                    ->required(fn (Get $get): bool => filled($get('password')))
                    ->dehydrated(false)
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (filled($value) && ! Hash::check((string) $value, Filament::auth()->user()->password)) {
                                $fail('Password lama tidak sesuai.');
                            }
                        },
                    ]),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
