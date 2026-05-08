<?php

declare(strict_types=1);

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Program;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function __construct(
        private ApplicationService $service,
        private ApplicationRepositoryInterface $applications,
    ) {
    }

    public function index(Request $request)
    {
        return view('candidate.applications.index', [
            'applications' => $this->applications->paginateForCandidate($request->user(), 15),
        ]);
    }

    /** Démarre ou reprend un brouillon pour un programme. */
    public function start(Program $program, Request $request)
    {
        $application = $this->service->startOrResumeDraft($program, $request->user());
        return redirect()->route('candidate.applications.edit', $application);
    }

    public function edit(Application $application)
    {
        $this->authorize('update', $application);
        $application->load(['program.applicationFields', 'responses', 'documents']);
        return view('candidate.applications.edit', compact('application'));
    }

    public function update(StoreApplicationRequest $request, Application $application)
    {
        $this->authorize('update', $application);

        $data = $request->validated();
        $files = collect($request->allFiles()['responses'] ?? [])->all();
        $responses = $data['responses'] ?? [];

        $application->update([
            'motivation'      => $data['motivation'] ?? $application->motivation,
            'project_summary' => $data['project_summary'] ?? $application->project_summary,
        ]);

        $this->service->saveResponses($application, $responses, $files);

        if ($request->boolean('submit')) {
            $this->service->submit($application);
            return redirect()->route('candidate.applications.show', $application)
                ->with('success', 'Candidature soumise. Vous recevrez un email de confirmation.');
        }

        return back()->with('success', 'Brouillon enregistré.');
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application);
        $application->load(['program', 'responses.field', 'documents']);
        return view('candidate.applications.show', compact('application'));
    }

    public function withdraw(Application $application)
    {
        $this->authorize('update', $application);
        $this->service->withdraw($application);
        return back()->with('success', 'Candidature retirée.');
    }
}
