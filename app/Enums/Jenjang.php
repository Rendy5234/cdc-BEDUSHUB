<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Jenjang: string implements HasLabel
{
    case SMA = 'sma';
    case SMK = 'smk';
    case D3 = 'd3';
    case S1 = 's1';
    case S2 = 's2';
    case S3 = 's3';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SMA => 'SMA',
            self::SMK => 'SMK',
            self::D3 => 'D3',
            self::S1 => 'S1',
            self::S2 => 'S2',
            self::S3 => 'S3',
        };
    }

    /**
     * Seluruh jenjang (value => label).
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
     * Jenjang pendidikan tinggi saja (D3/S1/S2/S3) untuk ProgramStudi.
     *
     * @return array<string, string>
     */
    public static function tertiaryLabels(): array
    {
        return [
            self::D3->value => self::D3->getLabel(),
            self::S1->value => self::S1->getLabel(),
            self::S2->value => self::S2->getLabel(),
            self::S3->value => self::S3->getLabel(),
        ];
    }
}
