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

    /**
     * Démarre une nouvelle candidature OU redirige vers la candidature
     * existante si la candidate a déjà postulé à ce programme.
     *
     * Règle métier : 1 candidature par programme et par candidate.
     */
    public function start(Program $program, Request $request)
    {
        try {
            $application = $this->service->startOrResumeDraft($program, $request->user());
        } catch (\RuntimeException $e) {
            return redirect()->route('candidate.dashboard')
                ->with('error', $e->getMessage());
        }

        // Si la candidate a déjà postulé : on redirige sans message d'erreur
        // (ce n'est pas une erreur, juste une reprise).
        if ($application->wasRecentlyCreated === false) {
            // Si le programme accepte encore les candidatures → édition possible
            if ($application->isEditable()) {
                return redirect()->route('candidate.applications.edit', $application)
                    ->with('info', 'Vous avez déjà une candidature pour ce programme. Vous pouvez la mettre à jour ici.');
            }
            // Sinon, simple consultation
            return redirect()->route('candidate.applications.show', $application)
                ->with('info', 'Vous avez déjà candidaté à ce programme.');
        }

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

        // Soumission demandée explicitement (bouton "Soumettre")
        if ($request->boolean('submit')) {
            // Si la candidature est encore en brouillon → première soumission
            if ($application->isDraft()) {
                $this->service->submit($application);
                return redirect()->route('candidate.applications.show', $application)
                    ->with('success', 'Candidature soumise. Vous recevrez un email de confirmation.');
            }
            // Si elle était déjà soumise et qu'on a juste mis à jour les réponses :
            // pas de re-soumission, on confirme la mise à jour.
            return redirect()->route('candidate.applications.show', $application)
                ->with('success', 'Mise à jour de votre candidature enregistrée.');
        }

        // Sauvegarde sans soumission
        $message = $application->isDraft()
            ? 'Brouillon enregistré.'
            : 'Mise à jour enregistrée. Vous pouvez modifier votre dossier tant que les candidatures sont ouvertes.';

        return back()->with('success', $message);
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
