<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Vérification d'email via lien signé.
 *
 * Cette implémentation NE NÉCESSITE PAS que l'utilisateur soit authentifié.
 * La validité du lien est garantie par la signature HMAC du middleware
 * `signed` (le secret APP_KEY étant nécessaire pour forger une URL valide).
 *
 * Avantage sécurité : on peut imposer la vérification AVANT toute connexion,
 * empêchant ainsi un attaquant de créer un compte avec l'email d'un tiers
 * puis d'y accéder.
 */
class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, int $id, string $hash): RedirectResponse
    {
        $user = User::find($id);

        // Sécurité : l'utilisateur doit exister ET le hash doit correspondre
        // au sha1 de son email actuel. Cela invalide automatiquement les
        // anciens liens si l'email a été changé.
        if (! $user || ! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            abort(403, 'Lien de vérification invalide.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('status', 'already-verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login')
            ->with('status', 'verified')
            ->with('verification_email', $user->email);
    }
}
