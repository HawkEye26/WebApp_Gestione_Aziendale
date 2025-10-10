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
    public function create()
    {
        return Inertia::render('Companies/Create', []);
    }

    // saving and validation system
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
        // reindirizzamento alla pagina index con messaggio
        return redirect()->route('companies.index')->with('message', 'Azienda registrata correttamente');
    }

    // print all saved companies
    public function index(Request $request)
    {
        $search = $request->get('search');

        $companies = Company::when($search, function ($query, $search) {
            return $query->where('company_name', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('province', 'like', "%{$search}%")
                ->orWhere('region', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->paginate(50)->appends(request()->query());

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    // single company printing
    public function show($id)
    {
        $company = Company::findOrFail($id);

        return response()->json($company);
    }

    // updating a specific field
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

        $company->update($validated);

        return redirect()->route('companies.index')->with('message', 'Azienda aggiornata correttamente');
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return Inertia::render('Companies/Edit', [
            'company' => $company,
        ]);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return back()->with(['message' => 'Azienda eliminata correttamente'], 200);
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:companies,id'
        ]);

        $count = Company::whereIn('id', $validated['ids'])->count();
        Company::whereIn('id', $validated['ids'])->delete();

        $message = $count === 1
            ? 'Azienda eliminata correttamente'
            : "{$count} aziende eliminate correttamente";

        return back()->with(['message' => $message], 200);
    }

    public function importPreview()
    {
        $user = Auth::user(); // prende l’utente loggato

        return Inertia::render('Companies/ImportPreview', [
            'auth' => [
                'user' => $user ? array_merge(
                    $user->only('id', 'name', 'email'),
                    ['can' => $user->getAllPermissions()->pluck('name')->toArray()]
                ) : null,
            ],
        ]);
    }


    public function import_store(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        Excel::import(new CompaniesImport, $validated['file']);

        return back()->with('success', 'File importato con successo!');
    }
}
