<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum JenisPelatihan: string implements HasLabel
{
    case WEBINAR = 'webinar';
    case SEMINAR = 'seminar';
    case WORKSHOP = 'workshop';
    case BOOTCAMP = 'bootcamp';
    case PELATIHAN = 'pelatihan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WEBINAR => 'Webinar',
            self::SEMINAR => 'Seminar',
            self::WORKSHOP => 'Workshop',
            self::BOOTCAMP => 'Bootcamp',
            self::PELATIHAN => 'Pelatihan',
        };
    }

    /**
     * Seluruh jenis pelatihan (value => label).
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
}
