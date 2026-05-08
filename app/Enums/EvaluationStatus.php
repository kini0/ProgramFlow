<?php

declare(strict_types=1);

namespace App\Enums;

enum EvaluationStatus: string
{
    case Assigned    = 'assigned';
    case InProgress  = 'in_progress';
    case Submitted   = 'submitted';

    public function label(): string
    {
        return match ($this) {
            self::Assigned   => 'À traiter',
            self::InProgress => 'En cours',
            self::Submitted  => 'Soumise',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Assigned   => 'gray',
            self::InProgress => 'amber',
            self::Submitted  => 'emerald',
        };
    }
}
