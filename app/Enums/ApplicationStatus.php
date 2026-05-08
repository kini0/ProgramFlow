<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Cycle de vie d'une candidature.
 */
enum ApplicationStatus: string
{
    case Draft        = 'draft';
    case Submitted    = 'submitted';
    case UnderReview  = 'under_review';
    case Shortlisted  = 'shortlisted';
    case Accepted     = 'accepted';
    case Rejected     = 'rejected';
    case Waitlisted   = 'waitlisted';
    case Withdrawn    = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Draft       => 'Brouillon',
            self::Submitted   => 'Soumise',
            self::UnderReview => 'En évaluation',
            self::Shortlisted => 'Présélectionnée',
            self::Accepted    => 'Acceptée',
            self::Rejected    => 'Refusée',
            self::Waitlisted  => 'Liste d\'attente',
            self::Withdrawn   => 'Retirée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft       => 'gray',
            self::Submitted   => 'blue',
            self::UnderReview => 'amber',
            self::Shortlisted => 'purple',
            self::Accepted    => 'emerald',
            self::Rejected    => 'red',
            self::Waitlisted  => 'orange',
            self::Withdrawn   => 'slate',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Accepted, self::Rejected, self::Withdrawn], true);
    }
}
