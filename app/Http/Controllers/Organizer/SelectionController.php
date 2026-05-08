<?php

declare(strict_types=1);

namespace App\Http\Controllers\Organizer;

use App\Exports\ApplicationsExport;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Services\SelectionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SelectionController extends Controller
{
    public function __construct(private SelectionService $service)
    {
    }

    public function show(Program $program)
    {
        $this->authorize('view', $program);

        return view('organizer.selection.show', [
            'program' => $program,
            'ranking' => $this->service->ranking($program, 200),
        ]);
    }

    public function shortlist(Request $request, Program $program)
    {
        $this->authorize('update', $program);
        $data = $request->validate(['count' => ['required', 'integer', 'min:1', 'max:500']]);
        $count = $this->service->autoShortlist($program, (int) $data['count']);

        return back()->with('success', "Présélection automatique : $count candidatures.");
    }

    public function lock(Program $program)
    {
        $this->authorize('update', $program);
        $this->service->lockSelection($program);
        return back()->with('success', 'Sélection verrouillée. Le programme passe en phase active.');
    }

    public function exportExcel(Program $program)
    {
        $this->authorize('view', $program);
        return Excel::download(new ApplicationsExport($program), 'candidatures-'.$program->slug.'.xlsx');
    }

    public function exportPdf(Program $program)
    {
        $this->authorize('view', $program);
        $ranking = $this->service->ranking($program, 500);
        $pdf = Pdf::loadView('exports.ranking-pdf', compact('program', 'ranking'));
        return $pdf->download('classement-'.$program->slug.'.pdf');
    }
}
