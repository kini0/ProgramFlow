<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 * Documentation publique de ProgramFlow.
 *
 * Pages accessibles à tous (avec ou sans compte) pour expliquer
 * les parcours utilisateur de chaque rôle.
 */
class HelpController extends Controller
{
    /**
     * Sommaire de l'aide.
     */
    public function index()
    {
        return view('help.index');
    }

    /**
     * Guide par rôle. $role doit être l'un de :
     *   admin, organizer, jury, candidate, partner
     */
    public function role(string $role)
    {
        $allowed = ['admin', 'organizer', 'jury', 'candidate', 'partner'];
        abort_unless(in_array($role, $allowed, true), 404);

        return view('help.role-'.$role);
    }

    public function faq()
    {
        return view('help.faq');
    }

    public function glossary()
    {
        return view('help.glossary');
    }
}
