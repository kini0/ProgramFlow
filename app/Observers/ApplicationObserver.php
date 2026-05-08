<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

/**
 * Observer pattern : trace toutes les transitions de statut
 * et déclenche les effets de bord nécessaires.
 */
class ApplicationObserver
{
    public function created(Application $application): void
    {
        Log::info('Application created', ['id' => $application->id, 'ref' => $application->reference]);
    }

    public function updating(Application $application): void
    {
        if (! $application->isDirty('status')) {
            return;
        }

        $original = $application->getOriginal('status');
        $new      = $application->status;

        Log::info('Application status changed', [
            'reference' => $application->reference,
            'from'      => $original,
            'to'        => $new instanceof ApplicationStatus ? $new->value : $new,
        ]);
    }

    public function deleted(Application $application): void
    {
        Log::warning('Application deleted', ['id' => $application->id, 'ref' => $application->reference]);
    }
}
