<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Téléchargement contrôlé des documents privés.
 *
 * Les fichiers sont stockés sur le disque "documents" (storage/app/private/documents)
 * et ne sont jamais exposés publiquement. L'accès est vérifié à chaque requête.
 */
class DocumentController extends Controller
{
    public function download(Request $request, Document $document): StreamedResponse
    {
        $this->authorizeAccess($request, $document);

        abort_unless(
            Storage::disk($document->disk)->exists($document->path),
            404,
            'Fichier introuvable.'
        );

        return Storage::disk($document->disk)->download(
            $document->path,
            $document->original_name,
        );
    }

    /**
     * Logique d'autorisation centralisée :
     *   - Admin : tout
     *   - Uploader : son propre fichier
     *   - Organisateur du programme : documents de candidatures du programme
     *   - Jury : documents des candidatures qui lui sont attribuées
     */
    protected function authorizeAccess(Request $request, Document $document): void
    {
        $user = $request->user();
        abort_unless($user, 403);

        if ($user->hasRole(UserRole::Admin->value)) {
            return;
        }
        if ($user->id === $document->uploaded_by) {
            return;
        }

        if ($document->documentable_type === Application::class) {
            /** @var Application|null $app */
            $app = $document->documentable;
            if (! $app) {
                abort(404);
            }
            if ($user->hasRole(UserRole::Organizer->value)
                && $user->programsAsOrganizer()->whereKey($app->program_id)->exists()) {
                return;
            }
            if ($user->hasRole(UserRole::Jury->value)
                && $app->evaluations()->where('jury_id', $user->id)->exists()) {
                return;
            }
        }

        abort(403, 'Accès refusé à ce document.');
    }
}
