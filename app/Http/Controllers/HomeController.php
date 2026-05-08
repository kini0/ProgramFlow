<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Repositories\Contracts\ProgramRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Aiguillage : page d'accueil publique + redirection après login.
 */
class HomeController extends Controller
{
    public function index(ProgramRepositoryInterface $programs)
    {
        return view('public.home', [
            'openPrograms' => $programs->listOpen(),
        ]);
    }

    public function show(string $slug, ProgramRepositoryInterface $programs)
    {
        $program = $programs->findPublic($slug);
        abort_unless($program, 404);

        return view('public.program', compact('program'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        return match (true) {
            $user->hasRole(UserRole::Admin->value)     => redirect()->route('admin.dashboard'),
            $user->hasRole(UserRole::Organizer->value) => redirect()->route('organizer.dashboard'),
            $user->hasRole(UserRole::Jury->value)      => redirect()->route('jury.dashboard'),
            $user->hasRole(UserRole::Partner->value)   => redirect()->route('partner.dashboard'),
            default                                    => redirect()->route('candidate.dashboard'),
        };
    }
}
