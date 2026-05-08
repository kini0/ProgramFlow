<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jury;

use App\Http\Controllers\Controller;
use App\Services\EvaluationService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, EvaluationService $service)
    {
        return view('jury.dashboard', [
            'pending' => $service->pendingForJury($request->user()),
        ]);
    }
}
