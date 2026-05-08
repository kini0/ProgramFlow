<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Cycle de vie d'un programme.
 *
 * draft       : brouillon, invisible côté public
 * published   : publié, visible mais candidatures non ouvertes
 * open        : candidatures ouvertes
 * review      : candidatures en cours d'évaluation par le jury
 * selection   : phase de sélection finale
 * active      : programme en cours (formations, ateliers)
 * completed   : programme terminé
 * archived    : archivé, lecture seule
 */
enum ProgramStatus: string
{
    case Draft      = 'draft';
    case Published  = 'published';
    case Open       = 'open';
    case Review     = 'review';
    case Selection  = 'selection';
    case Active     = 'active';
    case Completed  = 'completed';
    case Archived   = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Brouillon',
            self::Published => 'Publié',
            self::Open      => 'Candidatures ouvertes',
            self::Review    => 'Évaluation jury',
            self::Selection => 'Sélection finale',
            self::Active    => 'En cours',
            self::Completed => 'Terminé',
            self::Archived  => 'Archivé',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft     => 'gray',
            self::Published => 'blue',
            self::Open      => 'emerald',
            self::Review    => 'amber',
            self::Selection => 'purple',
            self::Active    => 'brand',
            self::Completed => 'teal',
            self::Archived  => 'slate',
        };
    }

    public function acceptsApplications(): bool
    {
        return $this === self::Open;
    }
}
