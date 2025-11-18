<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompaniesImport;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    // Mostra form per inserimento nuova azienda
    public function create()
    {
        return Inertia::render('Companies/Create', []);
    }

    // Salvataggio nuova azienda
    public function store(Request $request)
    {

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'zip_code' => 'required|string|regex:/^[A-Za-z0-9 \-]+$/|min:5',
            'city' => 'required|string|max:255',
            'province' => 'required|string|size:2|regex:/^[A-Za-z]{2}$/',
            'region' => 'required|string|max:255|regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/',
            'email' => 'required|email|max:255|unique:companies,email',
        ]);

        Company::create($validated);
        
        // Reindirizzamento alla pagina index con messaggio
        return redirect()->route('companies.index')->with('message', 'Azienda registrata correttamente');
    }

    // Lista aziende con ricerca
    public function index(Request $request)
    {
        // Recupera il campo del campo search
        $search = $request->get('search');

        // Controlla che la variabile $search sia piena per avviare la ricerca con i filtri altrimenti salta la funzione
        $companies = Company::when($search, function ($query, $search) {
            // Viene cercato in tutti i record, anche se non contiene precisamente quella parola con l'operatore Like
            return $query->where('company_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('province', 'like', "%{$search}%")
                ->orWhere('region', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"); 
        })->paginate(50)->appends(request()->query());

        // Mostra la lista e mantiene il campo ricerca compilato
        return Inertia::render('Companies/Index', [
            'companies' => $companies,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    // Dettagli per singola azienda in formato JSON
    public function show($id)
    {
        // cerca il campo se non lo trova genera la pagina 404 invece di lavorare con un campo nullo
        $company = Company::findOrFail($id);

        return response()->json($company);
    }

    // Mostra il form per la modifica con i campi gia compilati dall'azienda attuale
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return Inertia::render('Companies/Edit', [
            'company' => $company,
        ]);
    }

    // Modifica azienda
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $validated = $request->validate([
            'company_name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'zip_code' => 'sometimes|required|string|regex:/^[A-Za-z0-9 \-]+$/|max:5',
            'city' => 'sometimes|required|string|max:255',
            'province' => 'sometimes|required|string|max:255',
            'region' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
        ]);

        // validated contiene solo i campi modificati e li aggiorna
        $company->update($validated);

        return redirect()->route('companies.index')->with('message', 'Azienda aggiornata correttamente');
    }


    // Elimina azienda
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return back()->with(['message' => 'Azienda eliminata correttamente'], 200);
    }

    // Elimina più aziende contemporaneamente
    public function bulkDestroy(Request $request)
    {
        
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:companies,id'
        ]);

        // Tiene il conto delle aziende selezionate 
        $count = Company::whereIn('id', $validated['ids'])->count();
        // Cancella tutte le aziende selezionate in ids
        Company::whereIn('id', $validated['ids'])->delete();

        $message = $count === 1
            // Singola azienda
            ? 'Azienda eliminata correttamente'
            // Più aziende
            : "{$count} aziende eliminate correttamente";

        return back()->with(['message' => $message], 200);
    }

    // Pagina import se utente ha i permessi
    public function importPreview()
    {
        // prende l’utente loggato
        $user = Auth::user(); 

        return Inertia::render('Companies/ImportPreview', [
            'auth' => [
                // Se User esiste allora prendi solo i campi selezionati e i permessi e uniscili in un array per passarli al frontend
                'user' => $user ? array_merge(
                    $user->only('id', 'name', 'email'),
                    ['can' => $user->getAllPermissions()->pluck('name')->toArray()]
                ) : null,
            ],
        ]);
    }


    // Carica e importa file
    public function import_store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,json|max:10240',
        ]);

        // Importa e legge con la classe il file inserito
        Excel::import(new CompaniesImport, $validated['file']);

        return back()->with('success', 'File importato con successo!');
    }
}
