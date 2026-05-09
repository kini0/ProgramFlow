<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Affiche la page invitant la candidate à vérifier son email,
 * ou redirige vers le dashboard si l'email est déjà vérifié.
 */
class EmailVerificationPromptController extends Controller
{
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard'))
            : view('auth.verify-email');
    }
}
