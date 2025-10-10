<?php

namespace App\Providers;

// use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap for pagination.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Inertia::share([
            'auth' => fn() => [
                'user' => Auth::check() ? [
                    'id' => Auth::id(),
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'roles' => Auth::user()->getRoleNames(), // ruoli
                    'permissions' => Auth::user()->getAllPermissions()->pluck('name'), // permessi
                ] : null,
            ],
        ]);

        
    }
}
