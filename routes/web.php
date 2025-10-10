<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Welcome', [

        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard dinamica
Route::get('/dashboard', function () {
    // Conta le righe, filtra per mese e anno corrente e restituisce il numero con count() 
    $totalCompanies = \App\Models\Company::count();
    $companiesThisMonth = \App\Models\Company::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    // Seleziona le regioni e il conta le aziende per regioni, li ordina con un limite di 5
    $companiesByRegion = \App\Models\Company::select('region', DB::raw('count(*) as total'))
        ->groupBy('region')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();
    // Ordina le aziende dalla più vecchia
    $recentCompanies = \App\Models\Company::latest()->limit(5)->get();

    return Inertia::render('Dashboard', [
        'stats' => [
            'total' => $totalCompanies,
            'thisMonth' => $companiesThisMonth,
            'byRegion' => $companiesByRegion,
            'recent' => $recentCompanies
        ]
    ]);
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
    Route::get('/companies/import-preview', [CompanyController::class, 'importPreview'])
        ->name('companies.importPreview');

    Route::post('/companies/import-preview', [CompanyController::class, 'import_store'])
        ->name('companies.importStore');
});


require __DIR__ . '/auth.php';
