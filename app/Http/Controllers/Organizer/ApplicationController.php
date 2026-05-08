<?php

declare(strict_types=1);

namespace App\Http\Controllers\Organizer;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Program;
use App\Models\User;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Services\ApplicationService;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationRepositoryInterface $applications,
        private ApplicationService $service,
        private EvaluationService $evaluationService,
    ) {
    }

    public function index(Program $program, Request $request)
    {
        $this->authorize('view', $program);

        return view('organizer.applications.index', [
            'program'      => $program,
            'applications' => $this->applications->paginateForProgram($program, $request->only(['search', 'status'])),
            'stats'        => $this->applications->statsForProgram($program),
            'statuses'     => ApplicationStatus::cases(),
        ]);
    }

    public function show(Program $program, Application $application)
    {
        abort_unless($application->program_id === $program->id, 404);
        $this->authorize('view', $application);

        $application->load(['responses.field', 'documents', 'evaluations.jury', 'evaluations.scores.criterion', 'comments.user']);

        return view('organizer.applications.show', compact('program', 'application'));
    }

    public function assignJury(Request $request, Program $program, Application $application)
    {
        $this->authorize('decide', $application);
        abort_unless($application->program_id === $program->id, 404);

        $data = $request->validate([
            'jury_ids'   => ['required', 'array', 'min:1'],
            'jury_ids.*' => ['integer', 'exists:users,id'],
        ]);

        foreach ($data['jury_ids'] as $juryId) {
            $jury = User::findOrFail($juryId);
            $this->evaluationService->assign($application, $jury);
        }

        return back()->with('success', 'Jury attribués avec succès.');
    }

    public function decide(Request $request, Program $program, Application $application)
    {
        $this->authorize('decide', $application);
        abort_unless($application->program_id === $program->id, 404);

        $data = $request->validate([
            'status'           => ['required', 'in:accepted,rejected,waitlisted,shortlisted'],
            'decision_reason'  => ['nullable', 'string', 'max:2000'],
        ]);

        $this->service->decide(
            $application,
            ApplicationStatus::from($data['status']),
            $data['decision_reason'] ?? null,
            $request->user(),
        );

        return back()->with('success', 'Décision enregistrée et candidate notifiée.');
    }
}
