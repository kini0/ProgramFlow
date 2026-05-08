<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware utilitaire : vérifie qu'un utilisateur possède au moins
 * un des rôles passés en paramètre. Complémentaire au middleware
 * `role:` de Spatie pour les besoins métier spécifiques.
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            abort(403, 'Compte inactif ou non authentifié.');
        }

        if (empty($roles) || $user->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'Vous n\'avez pas le rôle requis pour accéder à cette ressource.');
    }
}
