<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreActivityReportRequest;
use App\Models\ActivityReport;
use App\Models\Document;
use App\Models\Program;
use App\Services\ActivityReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityReportController extends Controller
{
    public function __construct(private ActivityReportService $service)
    {
    }

    public function index(Program $program)
    {
        $this->authorize('view', $program);
        $program->load(['activityReports.creator', 'activityReports.galleryImages']);

        return view('admin.activity-reports.index', compact('program'));
    }

    public function create(Program $program)
    {
        $this->authorize('update', $program);
        return view('admin.activity-reports.create', [
            'program'  => $program,
            'sessions' => $program->sessions()->orderByDesc('starts_at')->get(),
        ]);
    }

    public function store(StoreActivityReportRequest $request, Program $program)
    {
        $report = $this->service->create(
            $request->validated(),
            $program,
            $request->user(),
            collect($request->allFiles())->all(),
        );

        return redirect()->route('admin.programs.activityReports.show', [$program, $report])
            ->with('success', 'Rapport créé avec succès.');
    }

    public function show(Program $program, ActivityReport $report)
    {
        abort_unless($report->program_id === $program->id, 404);
        $this->authorize('view', $program);

        $report->load(['creator', 'session', 'reportFile', 'galleryImages', 'galleryVideos']);

        return view('admin.activity-reports.show', compact('program', 'report'));
    }

    public function edit(Program $program, ActivityReport $report)
    {
        abort_unless($report->program_id === $program->id, 404);
        $this->authorize('update', $program);

        return view('admin.activity-reports.edit', [
            'program'  => $program,
            'report'   => $report,
            'sessions' => $program->sessions()->orderByDesc('starts_at')->get(),
        ]);
    }

    public function update(StoreActivityReportRequest $request, Program $program, ActivityReport $report)
    {
        abort_unless($report->program_id === $program->id, 404);

        $this->service->update(
            $report,
            $request->validated(),
            collect($request->allFiles())->all(),
        );

        return redirect()->route('admin.programs.activityReports.show', [$program, $report])
            ->with('success', 'Rapport mis à jour.');
    }

    public function publish(Program $program, ActivityReport $report)
    {
        abort_unless($report->program_id === $program->id, 404);
        $this->authorize('update', $program);

        $this->service->publish($report);
        return back()->with('success', 'Rapport publié.');
    }

    public function destroy(Program $program, ActivityReport $report)
    {
        abort_unless($report->program_id === $program->id, 404);
        $this->authorize('update', $program);

        $report->delete();
        return redirect()->route('admin.programs.activityReports.index', $program)
            ->with('success', 'Rapport supprimé.');
    }

    /**
     * Suppression d'un média individuel (image, vidéo, fichier joint).
     */
    public function destroyMedia(Program $program, ActivityReport $report, Document $document)
    {
        abort_unless($report->program_id === $program->id, 404);
        abort_unless($document->documentable_id === $report->id
            && $document->documentable_type === ActivityReport::class, 404);
        $this->authorize('update', $program);

        Storage::disk($document->disk)->delete($document->path);
        $document->delete();

        return back()->with('success', 'Média supprimé.');
    }
}
