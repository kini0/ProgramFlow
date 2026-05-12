<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Renvoie un email de vérification.
 *
 * Endpoint public : on accepte un paramètre `email` plutôt que de se baser
 * sur l'utilisateur authentifié, car la candidate n'est PAS connectée tant
 * qu'elle n'a pas vérifié son email. On reste prudent en évitant de
 * révéler si un email existe ou non en base.
 */
class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $data['email'])->first();

        // Pour ne pas révéler l'existence du compte, on retourne toujours
        // le même message succès, même si l'email n'existe pas.
        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()
            ->with('status', 'verification-resent')
            ->with('verification_email', $data['email']);
    }
}
