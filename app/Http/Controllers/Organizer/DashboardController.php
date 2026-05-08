<?php

declare(strict_types=1);

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $programs = $user->programsAsOrganizer()->withCount('applications')->get();

        return view('organizer.dashboard', compact('programs'));
    }
}
