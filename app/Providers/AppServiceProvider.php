<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Compatibilité MySQL <5.7.7 / MariaDB <10.2.2 (utf8mb4 limitation).
        Schema::defaultStringLength(191);
    }
}
