<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SkillLevel: string implements HasLabel
{
    case PEMULA = 'pemula';
    case MENENGAH = 'menengah';
    case MAHIR = 'mahir';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PEMULA => 'Pemula',
            self::MENENGAH => 'Menengah',
            self::MAHIR => 'Mahir',
        };
    }

    /**
     * Urutan numerik untuk perhitungan skill gap (semakin tinggi = semakin mahir).
     */
    public function order(): int
    {
        return match ($this) {
            self::PEMULA => 1,
            self::MENENGAH => 2,
            self::MAHIR => 3,
        };
    }

    /**
     * Seluruh level (value => label).
     *
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->getLabel();
        }

        return $labels;
    }

    /**
     * Seluruh level (value => urutan numerik).
     *
     * @return array<string, int>
     */
    public static function orders(): array
    {
        $orders = [];

        foreach (self::cases() as $case) {
            $orders[$case->value] = $case->order();
        }

        return $orders;
    }
}
