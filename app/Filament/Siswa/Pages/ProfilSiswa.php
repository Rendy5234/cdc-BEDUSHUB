<?php

namespace App\Filament\Siswa\Pages;

use App\Enums\Jenjang;
use App\Models\Fakultas;
use App\Models\Institusi;
use App\Models\Jurusan;
use App\Models\KategoriMinat;
use App\Models\Profile;
use App\Models\ProgramStudi;
use App\Models\Skill;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;

class ProfilSiswa extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Profil';

    protected static ?string $title = 'Profil';

    protected static ?string $navigationLabel = 'Profil';

    protected static ?string $slug = 'profil';

    protected static string $view = 'filament.siswa.pages.profil-siswa';

    public ?array $data = [];

    public function mount(): void
    {
        $profile = auth()->user()->profile;

        $this->form->fill([
            'user_id' => auth()->id(),
            ...($profile?->only([
                'jenis_kelamin',
                'tanggal_lahir',
                'domisili_kecamatan',
                'jenjang',
                'institusi_id',
                'jurusan_id',
                'fakultas_id',
                'prodi_id',
                'tahun_lulus',
                'status',
                'no_telp',
                'foto',
            ]) ?? []),
            'skills' => auth()->user()->userSkills
                ->map(fn ($s) => ['skill_id' => $s->skill_id, 'level' => $s->level])
                ->values()
                ->toArray(),
            'minat' => auth()->user()->userMinat
                ->pluck('kategori_minat_id')
                ->toArray(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Profil')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Data Diri')
                            ->schema([
                                Hidden::make('user_id')
                                    ->default(fn () => auth()->id()),
                                Select::make('jenis_kelamin')
                                    ->options([
                                        'l' => 'Laki-laki',
                                        'p' => 'Perempuan',
                                    ]),
                                DatePicker::make('tanggal_lahir'),
                                TextInput::make('domisili_kecamatan')
                                    ->maxLength(255),
                                Select::make('jenjang')
                                    ->options(Jenjang::labels())
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('institusi_id', null);
                                        $set('jurusan_id', null);
                                        $set('fakultas_id', null);
                                        $set('prodi_id', null);
                                    }),
                                Select::make('institusi_id')
                                    ->label('Institusi')
                                    ->options(fn (Get $get) => Institusi::query()
                                        ->where('jenis', $this->jenisUntukJenjang($get('jenjang')))
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('jurusan_id', null);
                                        $set('fakultas_id', null);
                                        $set('prodi_id', null);
                                    })
                                    ->visible(fn (Get $get) => filled($get('jenjang'))),
                                Select::make('jurusan_id')
                                    ->label('Jurusan')
                                    ->options(fn (Get $get) => Jurusan::query()
                                        ->where('institusi_id', $get('institusi_id'))
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id'))
                                    ->searchable()
                                    ->visible(fn (Get $get) => in_array($get('jenjang'), ['sma', 'smk'], true)),
                                Select::make('fakultas_id')
                                    ->label('Fakultas')
                                    ->options(fn (Get $get) => Fakultas::query()
                                        ->where('institusi_id', $get('institusi_id'))
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('prodi_id', null))
                                    ->visible(fn (Get $get) => in_array($get('jenjang'), ['d3', 's1', 's2', 's3'], true)),
                                Select::make('prodi_id')
                                    ->label('Program Studi')
                                    ->options(fn (Get $get) => ProgramStudi::query()
                                        ->where('fakultas_id', $get('fakultas_id'))
                                        ->where('jenjang', $get('jenjang'))
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id'))
                                    ->searchable()
                                    ->visible(fn (Get $get) => in_array($get('jenjang'), ['d3', 's1', 's2', 's3'], true)),
                                Select::make('status')
                                    ->options([
                                        'aktif' => 'Aktif',
                                        'lulus' => 'Lulus',
                                    ])
                                    ->default('aktif')
                                    ->live(),
                                TextInput::make('tahun_lulus')
                                    ->numeric()
                                    ->minValue(1990)
                                    ->maxValue(2100)
                                    ->visible(fn (Get $get) => $get('status') === 'lulus'),
                                TextInput::make('no_telp')
                                    ->tel()
                                    ->maxLength(255),
                                FileUpload::make('foto')
                                    ->image()
                                    ->directory('profiles'),
                            ])
                            ->columns(2),
                        Tab::make('Skill')
                            ->schema([
                                Repeater::make('skills')
                                    ->label('Keahlian')
                                    ->schema([
                                        Select::make('skill_id')
                                            ->label('Keahlian')
                                            ->options(fn () => Skill::query()->orderBy('nama')->pluck('nama', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                        Select::make('level')
                                            ->options([
                                                'pemula' => 'Pemula',
                                                'menengah' => 'Menengah',
                                                'mahir' => 'Mahir',
                                            ])
                                            ->default('pemula')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->default([])
                                    ->addActionLabel('Tambah Keahlian')
                                    ->collapsible(),
                            ]),
                        Tab::make('Minat')
                            ->schema([
                                CheckboxList::make('minat')
                                    ->label('Kategori Minat')
                                    ->options(fn () => KategoriMinat::query()->orderBy('nama')->pluck('nama', 'id'))
                                    ->columns(2),
                            ]),
                    ]),
            ])
            ->columns(1)
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

        Profile::updateOrCreate(
            ['user_id' => auth()->id()],
            collect($data)->except(['skills', 'minat'])->toArray(),
        );

        $user = auth()->user();

        $user->userSkills()->forceDelete();
        foreach ($data['skills'] ?? [] as $skill) {
            if (filled($skill['skill_id'] ?? null)) {
                $user->userSkills()->create([
                    'skill_id' => $skill['skill_id'],
                    'level' => $skill['level'] ?? 'pemula',
                ]);
            }
        }

        $user->userMinat()->forceDelete();
        foreach ($data['minat'] ?? [] as $minatId) {
            $user->userMinat()->create([
                'kategori_minat_id' => $minatId,
            ]);
        }

        Notification::make()
            ->title('Profil berhasil disimpan')
            ->success()
            ->send();
    }

    protected function jenisUntukJenjang(?string $jenjang): ?string
    {
        return match ($jenjang) {
            'sma' => 'sma',
            'smk' => 'smk',
            'd3', 's1', 's2', 's3' => 'pt',
            default => null,
        };
    }
}


