<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ApplicationStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRole::Admin->value,
            UserRole::Organizer->value,
            UserRole::Jury->value,
            UserRole::Candidate->value,
        ]);
    }

    public function view(User $user, Application $app): bool
    {
        if ($user->id === $app->user_id) {
            return true;
        }
        if ($user->hasRole(UserRole::Organizer->value)) {
            return $user->programsAsOrganizer()->whereKey($app->program_id)->exists();
        }
        if ($user->hasRole(UserRole::Jury->value)) {
            return $app->evaluations()->where('jury_id', $user->id)->exists();
        }
        return $user->hasRole(UserRole::Admin->value);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(UserRole::Candidate->value);
    }

    public function update(User $user, Application $app): bool
    {
        return $user->id === $app->user_id
            && $app->status === ApplicationStatus::Draft;
    }

    public function delete(User $user, Application $app): bool
    {
        return $this->update($user, $app);
    }

    public function decide(User $user, Application $app): bool
    {
        return $user->hasRole(UserRole::Admin->value)
            || ($user->hasRole(UserRole::Organizer->value)
                && $user->programsAsOrganizer()->whereKey($app->program_id)->exists());
    }
}
