<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Superadmin lolos semua permission.
        Gate::before(fn ($user) => $user instanceof \App\Models\User && $user->isSuper() ? true : null);
    }
}
