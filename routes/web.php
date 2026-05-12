<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Candidate;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Jury;
use App\Http\Controllers\Organizer;
use App\Http\Controllers\Partner;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programmes/{slug}', [HomeController::class, 'show'])->name('public.program');

// Documentation utilisateur publique
Route::prefix('aide')->name('help.')->group(function () {
    Route::get('/',              [\App\Http\Controllers\HelpController::class, 'index'])->name('index');
    Route::get('/role/{role}',   [\App\Http\Controllers\HelpController::class, 'role'])->name('role');
    Route::get('/faq',           [\App\Http\Controllers\HelpController::class, 'faq'])->name('faq');
    Route::get('/glossaire',     [\App\Http\Controllers\HelpController::class, 'glossary'])->name('glossary');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login',     [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login',    [AuthenticatedSessionController::class, 'store']);
    Route::get('register',  [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('forgot-password',  [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}',  [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password',         [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Vérification d'email
|--------------------------------------------------------------------------
| Trio de routes attendues par les notifications d'Illuminate :
|   - verification.notice : page invitant l'utilisateur à vérifier son email
|   - verification.verify : endpoint signé qui valide l'email
|   - verification.send   : ré-envoi du lien
*/
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Routes authentifiées : aiguillage du dashboard
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    /* Profil utilisateur (toutes les roles) */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',         [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/',       [ProfileController::class, 'update'])->name('update');
        Route::patch('password', [ProfileController::class, 'password'])->name('password');
        Route::delete('/',      [ProfileController::class, 'destroy'])->name('destroy');
    });

    /* Téléchargement sécurisé des documents privés */
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])
        ->name('documents.download');
});

/*
|--------------------------------------------------------------------------
| Espace Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:'.UserRole::Admin->value])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', Admin\DashboardController::class)->name('dashboard');
        Route::resource('users',    Admin\UserController::class);
        Route::resource('programs', Admin\ProgramController::class);
        Route::post('programs/{program}/archive', [Admin\ProgramController::class, 'archive'])->name('programs.archive');

        // Gestion des partenaires associés à un programme
        Route::post('programs/{program}/partners', [Admin\ProgramPartnerController::class, 'store'])
            ->name('programs.partners.store');
        Route::patch('programs/{program}/partners/{partner}', [Admin\ProgramPartnerController::class, 'update'])
            ->name('programs.partners.update');
        Route::delete('programs/{program}/partners/{partner}', [Admin\ProgramPartnerController::class, 'destroy'])
            ->name('programs.partners.destroy');

        // Gestion des membres (organisateurs/jurys) d'un programme
        Route::post('programs/{program}/members', [Admin\ProgramMemberController::class, 'store'])
            ->name('programs.members.store');
        Route::delete('programs/{program}/members/{user}', [Admin\ProgramMemberController::class, 'destroy'])
            ->name('programs.members.destroy');

        // Form Builder pour les champs dynamiques de candidature
        Route::get('programs/{program}/fields', [Admin\ApplicationFieldController::class, 'index'])
            ->name('programs.fields.index');
        Route::post('programs/{program}/fields', [Admin\ApplicationFieldController::class, 'store'])
            ->name('programs.fields.store');
        Route::patch('programs/{program}/fields/{field}', [Admin\ApplicationFieldController::class, 'update'])
            ->name('programs.fields.update');
        Route::delete('programs/{program}/fields/{field}', [Admin\ApplicationFieldController::class, 'destroy'])
            ->name('programs.fields.destroy');
        Route::post('programs/{program}/fields/reorder', [Admin\ApplicationFieldController::class, 'reorder'])
            ->name('programs.fields.reorder');

        Route::resource('partners', Admin\PartnerController::class);
        Route::get('reports',                       [Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/programs/{program}',    [Admin\ReportController::class, 'program'])->name('reports.program');
    });

/*
|--------------------------------------------------------------------------
| Rapports d'activité (admin + organizer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:'.UserRole::Admin->value.'|'.UserRole::Organizer->value])
    ->prefix('admin/programs/{program}/activity-reports')
    ->name('admin.programs.activityReports.')
    ->group(function () {
        Route::get('/',              [Admin\ActivityReportController::class, 'index'])->name('index');
        Route::get('/create',        [Admin\ActivityReportController::class, 'create'])->name('create');
        Route::post('/',             [Admin\ActivityReportController::class, 'store'])->name('store');
        Route::get('/{report}',      [Admin\ActivityReportController::class, 'show'])->name('show');
        Route::get('/{report}/edit', [Admin\ActivityReportController::class, 'edit'])->name('edit');
        Route::patch('/{report}',    [Admin\ActivityReportController::class, 'update'])->name('update');
        Route::delete('/{report}',   [Admin\ActivityReportController::class, 'destroy'])->name('destroy');
        Route::post('/{report}/publish', [Admin\ActivityReportController::class, 'publish'])->name('publish');
        Route::delete('/{report}/media/{document}', [Admin\ActivityReportController::class, 'destroyMedia'])->name('media.destroy');
    });

/*
|--------------------------------------------------------------------------
| Espace Organisateur
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:'.UserRole::Admin->value.'|'.UserRole::Organizer->value])
    ->prefix('organizer')->name('organizer.')->group(function () {
        Route::get('/', Organizer\DashboardController::class)->name('dashboard');

        Route::prefix('programs/{program:slug}')->name('programs.')->group(function () {
            // Action explicite : clôture des candidatures + lancement évaluation
            Route::post('start-evaluation', [Organizer\ApplicationController::class, 'startEvaluation'])
                ->name('startEvaluation');

            Route::get('applications',                      [Organizer\ApplicationController::class, 'index'])->name('applications.index');
            Route::get('applications/{application:reference}', [Organizer\ApplicationController::class, 'show'])->name('applications.show');
            Route::post('applications/{application:reference}/jury',     [Organizer\ApplicationController::class, 'assignJury'])->name('applications.assignJury');
            Route::post('applications/{application:reference}/decision', [Organizer\ApplicationController::class, 'decide'])->name('applications.decide');

            Route::get('selection',                 [Organizer\SelectionController::class, 'show'])->name('selection.show');
            Route::post('selection/shortlist',      [Organizer\SelectionController::class, 'shortlist'])->name('selection.shortlist');
            Route::post('selection/lock',           [Organizer\SelectionController::class, 'lock'])->name('selection.lock');
            Route::get('selection/export/excel',    [Organizer\SelectionController::class, 'exportExcel'])->name('selection.export.excel');
            Route::get('selection/export/pdf',      [Organizer\SelectionController::class, 'exportPdf'])->name('selection.export.pdf');

            Route::get('sessions',                  [Organizer\SessionController::class, 'index'])->name('sessions.index');
            Route::get('sessions/create',           [Organizer\SessionController::class, 'create'])->name('sessions.create');
            Route::post('sessions',                 [Organizer\SessionController::class, 'store'])->name('sessions.store');
            Route::get('sessions/{session}',        [Organizer\SessionController::class, 'show'])->name('sessions.show');
            Route::patch('sessions/{session}',      [Organizer\SessionController::class, 'update'])->name('sessions.update');
            Route::post('sessions/{session}/attendances', [Organizer\SessionController::class, 'markAttendance'])->name('sessions.attendances');
            Route::delete('sessions/{session}',     [Organizer\SessionController::class, 'destroy'])->name('sessions.destroy');
        });
    });

/*
|--------------------------------------------------------------------------
| Espace Jury
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:'.UserRole::Admin->value.'|'.UserRole::Jury->value])
    ->prefix('jury')->name('jury.')->group(function () {
        Route::get('/', Jury\DashboardController::class)->name('dashboard');
        Route::get('programs',                   [Jury\ProgramController::class, 'index'])->name('programs.index');
        Route::get('programs/{program:slug}',    [Jury\ProgramController::class, 'show'])->name('programs.show');
        Route::get('evaluations/{evaluation}',   [Jury\EvaluationController::class, 'show'])->name('evaluations.show');
        Route::patch('evaluations/{evaluation}', [Jury\EvaluationController::class, 'update'])->name('evaluations.update');
    });

/*
|--------------------------------------------------------------------------
| Espace Candidate
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:'.UserRole::Admin->value.'|'.UserRole::Candidate->value])
    ->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/', Candidate\DashboardController::class)->name('dashboard');
        Route::get('applications', [Candidate\ApplicationController::class, 'index'])->name('applications.index');
        Route::post('programs/{program:slug}/apply', [Candidate\ApplicationController::class, 'start'])->name('applications.start');
        Route::get('applications/{application:reference}',           [Candidate\ApplicationController::class, 'show'])->name('applications.show');
        Route::get('applications/{application:reference}/edit',      [Candidate\ApplicationController::class, 'edit'])->name('applications.edit');
        Route::patch('applications/{application:reference}',         [Candidate\ApplicationController::class, 'update'])->name('applications.update');
        Route::post('applications/{application:reference}/withdraw', [Candidate\ApplicationController::class, 'withdraw'])->name('applications.withdraw');
    });

/*
|--------------------------------------------------------------------------
| Espace Partenaire
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:'.UserRole::Admin->value.'|'.UserRole::Partner->value])
    ->prefix('partner')->name('partner.')->group(function () {
        Route::get('/', Partner\DashboardController::class)->name('dashboard');
    });
