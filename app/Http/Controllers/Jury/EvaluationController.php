<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvaluationRequest;
use App\Models\Evaluation;
use App\Services\EvaluationService;

class EvaluationController extends Controller
{
    public function __construct(private EvaluationService $service)
    {
    }

    public function show(Evaluation $evaluation)
    {
        $this->authorize('view', $evaluation);
        $evaluation->load([
            'application.candidate',
            'application.program.evaluationCriteria',
            'application.responses.field',
            'application.documents',
            'scores.criterion',
        ]);
        return view('jury.evaluations.show', compact('evaluation'));
    }

    public function update(StoreEvaluationRequest $request, Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);
        $this->service->submit(
            $evaluation,
            $request->validated()['scores'],
            $request->input('comment'),
        );
        return redirect()->route('jury.dashboard')->with('success', 'Évaluation soumise.');
    }
}
