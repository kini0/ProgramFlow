<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ProgramStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Models\Program;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use App\Services\ProgramService;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(
        private ProgramService $service,
        private ProgramRepositoryInterface $repo,
    ) {
        $this->authorizeResource(Program::class, 'program');
    }

    public function index(Request $request)
    {
        $programs = $this->repo->paginate(15, $request->only(['search', 'status']));

        return view('admin.programs.index', [
            'programs' => $programs,
            'statuses' => ProgramStatus::cases(),
        ]);
    }

    public function create()
    {
        return view('admin.programs.create', ['statuses' => ProgramStatus::cases()]);
    }

    public function store(StoreProgramRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('programs', 'public');
        }
        unset($data['cover_image']);

        $program = $this->service->create($data, $request->user());

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Programme créé avec succès.');
    }

    public function show(Program $program)
    {
        $program->load(['applicationFields', 'evaluationCriteria', 'partners', 'organizers', 'juries']);
        return view('admin.programs.show', compact('program'));
    }

    public function edit(Program $program)
    {
        return view('admin.programs.edit', [
            'program'  => $program,
            'statuses' => ProgramStatus::cases(),
        ]);
    }

    public function update(UpdateProgramRequest $request, Program $program)
    {
        $data = $request->validated();
        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('programs', 'public');
        }
        unset($data['cover_image']);

        $this->service->update($program, $data);

        return redirect()->route('admin.programs.show', $program)
            ->with('success', 'Programme mis à jour.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('admin.programs.index')->with('success', 'Programme supprimé.');
    }

    public function archive(Program $program)
    {
        $this->authorize('archive', $program);
        $this->service->archive($program);
        return back()->with('success', 'Programme archivé.');
    }
}
