<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\EvaluationStatus;
use App\Enums\UserRole;
use App\Models\Evaluation;
use App\Models\User;

class EvaluationPolicy
{
    public function view(User $user, Evaluation $eval): bool
    {
        return $user->id === $eval->jury_id
            || $user->hasRole(UserRole::Admin->value)
            || ($user->hasRole(UserRole::Organizer->value)
                && $user->programsAsOrganizer()->whereKey($eval->application->program_id)->exists());
    }

    public function update(User $user, Evaluation $eval): bool
    {
        return $user->id === $eval->jury_id
            && $eval->status !== EvaluationStatus::Submitted;
    }

    public function delete(User $user, Evaluation $eval): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }
}
