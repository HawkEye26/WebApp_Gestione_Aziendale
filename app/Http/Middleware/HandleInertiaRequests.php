<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

// Impostazioni globali e dati condivisi
class HandleInertiaRequests extends Middleware
{
    // Pagina principale HTML da usare la prima volta che si visita il sito
    protected $rootView = 'app';

    // Gestione della cache per forzare il refres se il file cambia
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    // Tutti i dati restituiti da questa funzione diventano props globali
    public function share(Request $request): array
    {
        $user = $request->user();
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            // apparizione messaggio
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => [
                'message' => fn() => $request->session()->get('message')
            ],

            // Rende globale lo status dell'admin per il front-end
            'permission' => [
                'canManageUsers' => fn() => $request->user()
                    ? $request->user()->only('id', 'name', 'email')
                    : null,
            ],

            // Controllare se l'utente loggato e admin
            'isAdmin' => $user?->hasRole('Admin')

        ];
    }
}
