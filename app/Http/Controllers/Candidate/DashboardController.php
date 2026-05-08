<?php

declare(strict_types=1);

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ApplicationRepositoryInterface;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        ApplicationRepositoryInterface $applications,
        ProgramRepositoryInterface $programs,
    ) {
        return view('candidate.dashboard', [
            'applications' => $applications->paginateForCandidate($request->user(), 5),
            'openPrograms' => $programs->listOpen(),
        ]);
    }
}
