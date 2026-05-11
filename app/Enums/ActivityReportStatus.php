<?php

declare(strict_types=1);

namespace App\Enums;

enum ActivityReportStatus: string
{
    case Draft     = 'draft';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Brouillon',
            self::Published => 'Publié',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft     => 'gray',
            self::Published => 'emerald',
        };
    }
}
