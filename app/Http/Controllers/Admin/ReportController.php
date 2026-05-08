<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function index(ReportService $reports)
    {
        return view('admin.reports.index', [
            'global'   => $reports->globalDashboard(),
            'programs' => Program::with('applications')->latest()->limit(10)->get(),
        ]);
    }

    public function program(Program $program, ReportService $reports)
    {
        return view('admin.reports.program', [
            'report' => $reports->programReport($program),
            'chart'  => $reports->applicationsByStatusForChart($program),
        ]);
    }
}
