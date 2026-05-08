<?php

declare(strict_types=1);

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSessionRequest;
use App\Models\Program;
use App\Models\ProgramSession;
use App\Models\User;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Program $program)
    {
        $this->authorize('view', $program);
        $program->load('sessions.facilitator');
        return view('organizer.sessions.index', compact('program'));
    }

    public function create(Program $program)
    {
        $this->authorize('update', $program);
        return view('organizer.sessions.create', [
            'program' => $program,
            'facilitators' => User::orderBy('last_name')->get(),
        ]);
    }

    public function store(StoreSessionRequest $request, Program $program)
    {
        $session = $program->sessions()->create($request->validated());
        return redirect()->route('organizer.programs.sessions.show', [$program, $session])
            ->with('success', 'Session planifiée.');
    }

    public function show(Program $program, ProgramSession $session)
    {
        $this->authorize('view', $program);
        abort_unless($session->program_id === $program->id, 404);
        $session->load(['attendances.user', 'tasks.assignee']);
        return view('organizer.sessions.show', compact('program', 'session'));
    }

    public function update(StoreSessionRequest $request, Program $program, ProgramSession $session)
    {
        abort_unless($session->program_id === $program->id, 404);
        $session->update($request->validated());
        return back()->with('success', 'Session mise à jour.');
    }

    public function markAttendance(Request $request, Program $program, ProgramSession $session)
    {
        $this->authorize('update', $program);
        $data = $request->validate([
            'attendances' => ['required', 'array'],
            'attendances.*.user_id' => ['required', 'integer', 'exists:users,id'],
            'attendances.*.status' => ['required', 'in:present,absent,excused,late'],
            'attendances.*.note'   => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data['attendances'] as $row) {
            $session->attendances()->updateOrCreate(
                ['user_id' => $row['user_id']],
                [
                    'status' => $row['status'],
                    'note'   => $row['note'] ?? null,
                    'marked_by' => $request->user()->id,
                ],
            );
        }

        return back()->with('success', 'Présences enregistrées.');
    }

    public function destroy(Program $program, ProgramSession $session)
    {
        $this->authorize('update', $program);
        abort_unless($session->program_id === $program->id, 404);
        $session->delete();
        return back()->with('success', 'Session supprimée.');
    }
}
