<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

// Pagina principale
Route::get('/', function () {
    return Inertia::render('Welcome', [

        // Controlla se esistono le rotte e versioni e stampa a schermo
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard dinamica
Route::get('/dashboard', function () {

    // Conta tutte le aziende
    $totalCompanies = \App\Models\Company::count();

    // Filtra a conta le aziende create nel mese
    $companiesThisMonth = \App\Models\Company::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // Seleziona le regioni e il conta le aziende per regioni, li ordina con un limite di 10
    $companiesByRegion = \App\Models\Company::select('region', DB::raw('count(*) as total'))
        ->groupBy('region')
        ->orderBy('total', 'desc')
        ->limit(10)
        ->get();

    // Ordina le aziende dalla più vecchia
    $recentCompanies = \App\Models\Company::latest()->limit(7)->get();

    // Ritorna i valori alla pagina in modo che sia in tempo reale
    return Inertia::render('Dashboard', [
        'stats' => [
            'total' => $totalCompanies,
            'thisMonth' => $companiesThisMonth,
            'byRegion' => $companiesByRegion,
            'recent' => $recentCompanies
        ]
    ]);

    // Accesso solo ad utenti loggati
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotte per gestire le opzioni CRUD
Route::get('/Company', [CompanyController::class, 'index'])->name('companies.index');
Route::get('/Company/Create', [CompanyController::class, 'create'])->name('companies.create');
Route::post('/Company/store', [CompanyController::class, 'store'])->name('companies.store');
Route::get('/Company/show/{id}', [CompanyController::class, 'show'])->name('companies.show');
Route::put('/Company/{id}', [CompanyController::class, 'update'])->name('companies.update');
Route::get('/Company/edit/{id}', [CompanyController::class, 'edit'])->name('companies.edit');
Route::delete('/Company/destroy/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
Route::delete('/Company/bulk-destroy', [CompanyController::class, 'bulkDestroy'])->name('companies.bulkDestroy');

// Rotte per import file esterno
Route::middleware(['auth', 'can:import files'])->group(function () {
    
    // Richiama il metodo importPreview e visualizza un'anteprima
    Route::get('/companies/import-preview', [CompanyController::class, 'importPreview'])
        ->name('companies.importPreview');

    // Richiama il metodo import_store, carica e salva il file nel db
    Route::post('/companies/import-preview', [CompanyController::class, 'import_store'])
        ->name('companies.importStore');
});

// File che contiene rotte di autenticazione obbligatorio
require __DIR__ . '/auth.php';
