<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Gestion des membres d'un programme (organisateurs, jurys, mentors, etc.).
 *
 * Le rôle métier (organizer/jury/...) est stocké dans la table pivot program_user.
 * Cela permet à un même utilisateur d'avoir plusieurs casquettes selon le programme.
 */
class ProgramMemberController extends Controller
{
    /**
     * Attache des utilisateurs au programme avec un rôle donné.
     */
    public function store(Request $request, Program $program): RedirectResponse
    {
        $this->authorize('update', $program);

        $data = $request->validate([
            'user_ids'   => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'role'       => ['required', Rule::in(['organizer', 'jury', 'mentor', 'speaker', 'participant'])],
        ]);

        // On utilise sync uniquement pour la combinaison [user_id, role]
        // afin de ne pas perdre les autres associations existantes.
        $payload = [];
        foreach ($data['user_ids'] as $id) {
            $payload[] = ['user_id' => $id, 'role' => $data['role']];
        }

        foreach ($payload as $row) {
            $exists = $program->members()
                ->wherePivot('user_id', $row['user_id'])
                ->wherePivot('role', $row['role'])
                ->exists();

            if (! $exists) {
                $program->members()->attach($row['user_id'], ['role' => $row['role']]);
            }
        }

        // Affecte le rôle global Spatie correspondant si l'utilisateur ne l'a pas
        if (in_array($data['role'], [UserRole::Organizer->value, UserRole::Jury->value], true)) {
            User::whereIn('id', $data['user_ids'])->get()->each(function (User $u) use ($data) {
                if (! $u->hasRole($data['role'])) {
                    $u->assignRole($data['role']);
                }
            });
        }

        return back()->with('success', count($data['user_ids']).' membre(s) ajouté(s) en tant que '.$data['role'].'.');
    }

    /**
     * Détache un membre selon son rôle dans le programme.
     */
    public function destroy(Request $request, Program $program, User $user): RedirectResponse
    {
        $this->authorize('update', $program);

        $data = $request->validate([
            'role' => ['required', Rule::in(['organizer', 'jury', 'mentor', 'speaker', 'participant'])],
        ]);

        $program->members()
            ->newPivotStatement()
            ->where('program_id', $program->id)
            ->where('user_id', $user->id)
            ->where('role', $data['role'])
            ->delete();

        return back()->with('success', 'Membre retiré du programme.');
    }
}
