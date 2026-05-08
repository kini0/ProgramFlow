<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $partner = $request->user()->partner;
        $programs = $partner ? $partner->programs()->withCount('applications')->get() : collect();

        return view('partner.dashboard', compact('partner', 'programs'));
    }
}
