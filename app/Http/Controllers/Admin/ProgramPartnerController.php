<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Gestion des partenaires associés à un programme.
 *
 * Endpoint imbriqué : /admin/programs/{program}/partners
 */
class ProgramPartnerController extends Controller
{
    /**
     * Attache un ou plusieurs partenaires au programme.
     */
    public function store(Request $request, Program $program): RedirectResponse
    {
        $this->authorize('update', $program);

        $data = $request->validate([
            'partner_ids'   => ['required', 'array', 'min:1'],
            'partner_ids.*' => ['integer', 'exists:partners,id'],
            'role'          => ['nullable', 'string', 'max:80'],
        ]);

        $payload = [];
        foreach ($data['partner_ids'] as $id) {
            $payload[$id] = ['role' => $data['role'] ?? null];
        }
        $program->partners()->syncWithoutDetaching($payload);

        return back()->with('success', count($data['partner_ids']).' partenaire(s) associé(s).');
    }

    /**
     * Met à jour le rôle d'un partenaire dans le programme.
     */
    public function update(Request $request, Program $program, Partner $partner): RedirectResponse
    {
        $this->authorize('update', $program);

        $data = $request->validate(['role' => ['nullable', 'string', 'max:80']]);
        $program->partners()->updateExistingPivot($partner->id, ['role' => $data['role'] ?? null]);

        return back()->with('success', 'Rôle du partenaire mis à jour.');
    }

    /**
     * Détache un partenaire du programme.
     */
    public function destroy(Program $program, Partner $partner): RedirectResponse
    {
        $this->authorize('update', $program);

        $program->partners()->detach($partner->id);

        return back()->with('success', 'Partenaire retiré du programme.');
    }
}
