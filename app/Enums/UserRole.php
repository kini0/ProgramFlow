<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Énumération des rôles métier.
 *
 * Les noms exacts sont utilisés par Spatie Permission pour identifier
 * les rôles côté base de données.
 */
enum UserRole: string
{
    case Admin       = 'admin';
    case Organizer   = 'organizer';
    case Jury        = 'jury';
    case Candidate   = 'candidate';
    case Partner     = 'partner';

    public function label(): string
    {
        return match ($this) {
            self::Admin     => 'Administrateur',
            self::Organizer => 'Organisateur',
            self::Jury      => 'Membre du jury',
            self::Candidate => 'Candidate',
            self::Partner   => 'Partenaire',
        };
    }

    /** @return string[] */
    public static function values(): array
    {
        return array_map(fn (self $r) => $r->value, self::cases());
    }
}
