<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

/**
 * Espace Jury — vue d'ensemble des programmes auxquels le jury
 * est associé.
 *
 * Règle métier importante : un jury membre d'un programme évalue
 * automatiquement TOUTES les candidatures soumises de ce programme,
 * sans avoir besoin d'attribution explicite. L'auto-création des
 * évaluations est gérée par EvaluationService::ensureEvaluationsForJury().
 */
class ProgramController extends Controller
{
    public function __construct(private EvaluationService $service)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Pour chaque programme dont je suis membre du jury, on s'assure
        // que les évaluations sont créées AVANT de calculer les compteurs.
        $myPrograms = $user->programsAsJury()->get();
        foreach ($myPrograms as $program) {
            $this->service->ensureEvaluationsForJury($program, $user);
        }

        $programs = $user->programsAsJury()
            ->withCount([
                'applications as total_applications' => function ($q) {
                    $q->whereNotIn('status', ['draft', 'withdrawn']);
                },
                'applications as my_evaluations_count' => function ($q) use ($user) {
                    $q->whereHas('evaluations', fn ($qq) => $qq->where('jury_id', $user->id));
                },
                'applications as my_pending_count' => function ($q) use ($user) {
                    $q->whereHas('evaluations', fn ($qq) => $qq
                        ->where('jury_id', $user->id)
                        ->whereIn('status', ['assigned', 'in_progress']));
                },
                'applications as my_done_count' => function ($q) use ($user) {
                    $q->whereHas('evaluations', fn ($qq) => $qq
                        ->where('jury_id', $user->id)
                        ->where('status', 'submitted'));
                },
            ])
            ->get();

        return view('jury.programs.index', [
            'programs' => $programs,
            'service'  => $this->service,
        ]);
    }

    public function show(Program $program, Request $request)
    {
        $user = $request->user();

        // Sécurité : le jury doit bien être associé à ce programme
        abort_unless(
            $user->programsAsJury()->whereKey($program->id)->exists(),
            403,
            'Vous n\'êtes pas membre du jury de ce programme.'
        );

        // S'assure que les évaluations sont créées pour toutes les
        // candidatures soumises (idempotent).
        $created = $this->service->ensureEvaluationsForJury($program, $user);

        $isOpenForEval = $this->service->isProgramOpenForEvaluation($program);

        // Toutes les évaluations attribuées à ce jury pour ce programme
        $evaluations = $user->evaluations()
            ->whereHas('application', fn ($q) => $q->where('program_id', $program->id))
            ->with(['application.candidate'])
            ->orderBy('status')
            ->orderBy('created_at')
            ->get();

        return view('jury.programs.show', [
            'program'        => $program,
            'evaluations'    => $evaluations,
            'isOpenForEval'  => $isOpenForEval,
            'createdCount'   => $created,
        ]);
    }
}
