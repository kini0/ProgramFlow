<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Application;
use App\Models\Evaluation;
use App\Models\Program;
use App\Policies\ApplicationPolicy;
use App\Policies\EvaluationPolicy;
use App\Policies\ProgramPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Enregistre les Policies et le Gate::before global.
 *
 * Note : la classe Illuminate\Foundation\Support\Providers\AuthServiceProvider
 * n'existe plus dans Laravel 11. On étend désormais directement ServiceProvider
 * et on lie chaque Policy via Gate::policy().
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        Program::class     => ProgramPolicy::class,
        Application::class => ApplicationPolicy::class,
        Evaluation::class  => EvaluationPolicy::class,
    ];

    public function register(): void
    {
    }

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Bypass : super-admin a tous les droits
        Gate::before(function ($user) {
            return $user->hasRole(\App\Enums\UserRole::Admin->value) ? true : null;
        });
    }
}
