<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Program;
use App\Models\User;

/**
 * Policy : qui peut faire quoi sur un Program ?
 *
 * Note : un Gate::before global accorde tous les droits aux Admins
 * (cf. AuthServiceProvider).
 */
class ProgramPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            UserRole::Admin->value,
            UserRole::Organizer->value,
            UserRole::Jury->value,
            UserRole::Partner->value,
        ]);
    }

    public function view(User $user, Program $program): bool
    {
        if ($user->hasRole(UserRole::Organizer->value)) {
            return $user->programsAsOrganizer()->whereKey($program->id)->exists()
                || $user->id === $program->created_by;
        }
        if ($user->hasRole(UserRole::Jury->value)) {
            return $user->programsAsJury()->whereKey($program->id)->exists();
        }
        if ($user->hasRole(UserRole::Partner->value)) {
            return $user->partner?->programs()->whereKey($program->id)->exists() ?? false;
        }
        return $user->hasRole(UserRole::Admin->value);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRole::Admin->value, UserRole::Organizer->value]);
    }

    public function update(User $user, Program $program): bool
    {
        if ($user->hasRole(UserRole::Admin->value)) {
            return true;
        }
        return $user->hasRole(UserRole::Organizer->value)
            && ($user->id === $program->created_by
                || $user->programsAsOrganizer()->whereKey($program->id)->exists());
    }

    public function delete(User $user, Program $program): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }

    public function archive(User $user, Program $program): bool
    {
        return $this->update($user, $program);
    }
}
