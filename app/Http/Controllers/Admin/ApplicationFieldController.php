<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationFieldRequest;
use App\Models\ApplicationField;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Form Builder admin pour les champs DYNAMIQUES (section spécifique au programme).
 *
 * Les champs de la section "dynamic" peuvent être ajoutés / modifiés /
 * supprimés / réordonnés. Les sections standard (identity, address, health,
 * etc.) ne sont JAMAIS exposées ici : elles sont gérées par le seeder
 * ProgramService::seedStandardFields() et restent fixes pour tous les programmes.
 */
class ApplicationFieldController extends Controller
{
    public function index(Program $program)
    {
        $this->authorize('update', $program);

        $fields = $program->applicationFields()
            ->where('section', 'dynamic')
            ->orderBy('order_column')
            ->get();

        return view('admin.programs.fields.index', compact('program', 'fields'));
    }

    public function store(StoreApplicationFieldRequest $request, Program $program)
    {
        $data = $request->validated();
        $data['section']      = 'dynamic';
        $data['key']          = $data['key'] ?: Str::slug($data['label'], '_');
        $data['is_required']  = (bool) ($data['is_required'] ?? false);
        $data['order_column'] = ((int) $program->applicationFields()->where('section', 'dynamic')->max('order_column')) + 1;

        // Garantit l'unicité du key au sein du programme
        $base = $data['key'];
        $i = 2;
        while ($program->applicationFields()->where('key', $data['key'])->exists()) {
            $data['key'] = $base.'_'.$i++;
        }

        $program->applicationFields()->create($data);

        return back()->with('success', 'Champ ajouté.');
    }

    public function update(StoreApplicationFieldRequest $request, Program $program, ApplicationField $field)
    {
        abort_unless($field->program_id === $program->id && $field->section === 'dynamic', 404);

        $data = $request->validated();
        $data['is_required'] = (bool) ($data['is_required'] ?? false);
        unset($data['key']); // Le key reste figé après création

        $field->update($data);
        return back()->with('success', 'Champ mis à jour.');
    }

    public function destroy(Program $program, ApplicationField $field)
    {
        abort_unless($field->program_id === $program->id && $field->section === 'dynamic', 404);
        $this->authorize('update', $program);

        $field->delete();
        return back()->with('success', 'Champ supprimé.');
    }

    /**
     * Réordonne les champs dynamiques en lot via une requête POST contenant
     * un tableau order_column => field_id.
     */
    public function reorder(Request $request, Program $program)
    {
        $this->authorize('update', $program);
        $data = $request->validate([
            'fields'    => ['required', 'array'],
            'fields.*'  => ['integer', 'exists:application_fields,id'],
        ]);

        foreach ($data['fields'] as $position => $fieldId) {
            ApplicationField::where('id', $fieldId)
                ->where('program_id', $program->id)
                ->where('section', 'dynamic')
                ->update(['order_column' => $position]);
        }

        return response()->json(['ok' => true]);
    }
}
