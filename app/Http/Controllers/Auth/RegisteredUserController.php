<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Inscription publique : crée toujours un compte avec le rôle "candidate".
 *
 * ⚠️ Politique de sécurité : la candidate N'EST PAS auto-connectée à
 * l'inscription. Elle doit d'abord vérifier son email via le lien envoyé,
 * puis se connecter normalement. Cela évite qu'un attaquant ne crée un
 * compte avec l'email d'un tiers et y accède sans preuve de possession.
 */
class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name'  => ['required', 'string', 'max:120'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
            'is_active'  => true,
        ]);

        $user->assignRole(UserRole::Candidate->value);

        // Déclenche l'envoi de l'email de vérification (listener par défaut
        // SendEmailVerificationNotification fourni par Laravel).
        event(new Registered($user));

        // PAS d'Auth::login() : la candidate doit d'abord vérifier son email.
        // On la redirige vers la page de connexion avec un message clair.
        return redirect()->route('login')
            ->with('status', 'verification-sent')
            ->with('verification_email', $data['email']);
    }
}
