# 🤝 Guida per Contribuire - WebAppGAD

## Introduzione

Benvenuto nella guida per contribuire al progetto WebAppGAD! Questa documentazione ti aiuterà a configurare l'ambiente di sviluppo e a seguire le best practices per contribuire efficacemente al progetto.

## Setup Ambiente di Sviluppo

### Prerequisiti

-   **PHP**: 8.1 o superiore
-   **Node.js**: 16.x o superiore
-   **Composer**: 2.x
-   **Git**: Ultima versione
-   **IDE**: VS Code (consigliato) con estensioni PHP/Vue.js

### Estensioni VS Code Consigliate

```json
{
    "recommendations": [
        "bmewburn.vscode-intelephense-client",
        "vue.volar",
        "bradlc.vscode-tailwindcss",
        "onecentlin.laravel-blade",
        "ryannaddy.laravel-artisan",
        "ms-vscode.vscode-json"
    ]
}
```

### Clone e Setup Iniziale

```bash
# 1. Fork del repository su GitHub (se applicabile)
# 2. Clone del repository
git clone https://github.com/[HawkEye26]/WebAppGAD.git
# oppure se hai accesso diretto:
git clone [URL-DEL-REPOSITORY]
cd WebAppGAD

# 3. Aggiungi remote upstream (se hai fatto un fork)
git remote add upstream [URL-REPOSITORY-ORIGINALE]

# 4. Installa dipendenze
composer install
npm install

# 5. Setup ambiente
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed

# 6. Avvia server di sviluppo
npm run dev:all
```

## Workflow di Contribuzione

### Branch Strategy

Utilizziamo **GitHub Flow** con questi tipi di branch:

-   `main`: Branch principale di produzione
-   `develop`: Branch di sviluppo (features integrate)
-   `feature/*`: Nuove funzionalità
-   `bugfix/*`: Correzioni di bug
-   `hotfix/*`: Fix critici per produzione

### Nomenclatura Branch

```bash
# Feature branches
feature/user-management
feature/company-import
feature/team-permissions

# Bug fixes
bugfix/login-validation
bugfix/company-duplicate

# Hotfixes
hotfix/security-patch
hotfix/critical-bug
```

### Processo di Contribuzione

1. **Crea Issue**

    - Descrivi il problema/funzionalità
    - Usa i template forniti
    - Aggiungi label appropriate

2. **Crea Branch**

    ```bash
    git checkout develop
    git pull upstream develop
    git checkout -b feature/nome-funzionalita
    ```

3. **Sviluppa**

    - Segui coding standards
    - Scrivi test
    - Aggiorna documentazione

4. **Commit**

    ```bash
    git add .
    git commit -m "feat: add user management functionality"
    ```

5. **Push e Pull Request**
    ```bash
    git push origin feature/nome-funzionalita
    # Crea PR su GitHub
    ```

## Coding Standards

### PHP (Laravel)

Seguiamo **PSR-12** con alcune regole aggiuntive:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\CreateCompanyRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(): Response
    {
        $companies = auth()->user()
            ->companies()
            ->with('team')
            ->latest()
            ->paginate(15);

        return Inertia::render('Companies/Index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Store a newly created company.
     */
    public function store(CreateCompanyRequest $request): RedirectResponse
    {
        auth()->user()->companies()->create($request->validated());

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }
}
```

#### Regole PHP

-   **Indentazione**: 4 spazi
-   **Lunghezza linea**: Max 120 caratteri
-   **Naming**: camelCase per metodi, snake_case per variabili DB
-   **Type hints**: Sempre specificare tipi di ritorno
-   **Docblocks**: Per metodi pubblici e complessi

### JavaScript/Vue.js

Seguiamo **Vue.js 3 Style Guide**:

```vue
<template>
    <div class="bg-white shadow-xl sm:rounded-lg">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ company.name }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ company.vat_number }}
            </p>

            <div class="mt-4 flex space-x-3">
                <SecondaryButton @click="editCompany"> Edit </SecondaryButton>

                <DangerButton @click="deleteCompany"> Delete </DangerButton>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

// Props
const props = defineProps({
    company: {
        type: Object,
        required: true,
    },
});

// Methods
const editCompany = () => {
    router.visit(`/companies/${props.company.id}/edit`);
};

const deleteCompany = () => {
    if (confirm("Are you sure?")) {
        router.delete(`/companies/${props.company.id}`);
    }
};
</script>
```

#### Regole Vue.js

-   **Composition API**: Usa `<script setup>`
-   **Naming**: PascalCase per componenti, camelCase per props/methods
-   **Props**: Sempre tipizzate con validazione
-   **Events**: Usa `defineEmits()` per eventi custom
-   **Reattività**: Usa `ref()` e `reactive()` appropriatamente

### CSS/Tailwind

```vue
<template>
  <!-- ✅ Buono: Classi organizzate logicamente -->
  <div class="
    bg-white rounded-lg shadow-md
    p-6 mb-4
    hover:shadow-lg transition-shadow duration-200
    sm:p-8
  ">
    <h3 class="text-xl font-semibold text-gray-900 mb-2">
      Title
    </h3>
  </div>

  <!-- ❌ Evitare: Troppo inline -->
  <div class="bg-white p-6 rounded-lg shadow-md mb-4 hover:shadow-lg transition-shadow duration-200 sm:p-8">
</template>
```

## Commit Guidelines

### Conventional Commits

Usiamo [Conventional Commits](https://conventionalcommits.org/):

```bash
# Formato
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Tipi di Commit

-   `feat`: Nuova funzionalità
-   `fix`: Correzione bug
-   `docs`: Aggiornamenti documentazione
-   `style`: Formattazione codice
-   `refactor`: Refactoring senza nuove funzionalità
-   `test`: Aggiunta/modifica test
-   `chore`: Aggiornamenti build/tool

### Esempi

```bash
# Feature
feat(companies): add bulk import functionality

# Bug fix
fix(auth): resolve login redirect issue

# Documentation
docs(api): update endpoint documentation

# Refactoring
refactor(user-service): extract validation logic

# Multiple changes
feat(teams): add member management

- Add invite member functionality
- Implement role-based permissions
- Add member removal feature

Closes #123
```

## Testing Guidelines

### Copertura Test

Mantieni almeno **80% di code coverage**:

```bash
# Verifica coverage
php artisan test --coverage

# Coverage dettagliato
php artisan test --coverage-html=coverage
```

### Tipi di Test Richiesti

1. **Feature Tests**: Per ogni controller action
2. **Unit Tests**: Per model methods e service classes
3. **Integration Tests**: Per funzionalità complesse

### Esempio Test Structure

```php
<?php

namespace Tests\Feature\Companies;

use App\Models\{User, Company};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_companies(): void
    {
        // Arrange
        $user = User::factory()->create();
        Company::factory()->count(3)->create(['user_id' => $user->id]);

        // Act
        $response = $this->actingAs($user)->get('/companies');

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Companies/Index')
                 ->has('companies.data', 3)
        );
    }
}
```

## Database Guidelines

### Migrations

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('vat_number', 50)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('website')->nullable();
            $table->string('sector', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
```

### Models

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, SoftDeletes, Factories\HasFactory};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'vat_number', 'address',
        'email', 'phone', 'website', 'sector'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Scopes
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeInSector($query, string $sector)
    {
        return $query->where('sector', $sector);
    }
}
```

## API Guidelines

### RESTful Routes

```php
// routes/web.php
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Companies Resource
    Route::resource('companies', CompanyController::class);

    // Custom routes dopo resource
    Route::post('companies/import', [CompanyController::class, 'import'])->name('companies.import');
    Route::get('companies/export', [CompanyController::class, 'export'])->name('companies.export');
});
```

### Response Format

```php
// Success Response
return response()->json([
    'data' => $company,
    'message' => 'Company created successfully',
    'status' => 'success'
], 201);

// Error Response
return response()->json([
    'message' => 'Validation failed',
    'errors' => $validator->errors(),
    'status' => 'error'
], 422);
```

## Sicurezza

### Authorization

```php
// Policy
class CompanyPolicy
{
    public function view(User $user, Company $company): bool
    {
        return $user->id === $company->user_id ||
               $user->belongsToTeam($company->team);
    }

    public function update(User $user, Company $company): bool
    {
        return $user->id === $company->user_id ||
               $user->hasTeamPermission($company->team, 'update');
    }
}

// Controller
public function show(Company $company)
{
    $this->authorize('view', $company);

    return Inertia::render('Companies/Show', [
        'company' => $company->load('team'),
    ]);
}
```

### Validation

```php
class CreateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gestito da middleware auth
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:50', 'unique:companies'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Il nome dell\'azienda è obbligatorio.',
            'vat_number.unique' => 'Partita IVA già esistente.',
        ];
    }
}
```

## Pull Request Guidelines

### Template PR

```markdown
## Descrizione

Breve descrizione delle modifiche apportate.

## Tipo di Modifica

-   [ ] Bug fix (non-breaking change che risolve un issue)
-   [ ] Nuova feature (non-breaking change che aggiunge funzionalità)
-   [ ] Breaking change (fix o feature che causerebbe malfunzionamenti)
-   [ ] Aggiornamento documentazione

## Testing

-   [ ] I test esistenti passano
-   [ ] Aggiunti nuovi test per le modifiche
-   [ ] Coverage mantenuto/migliorato

## Checklist

-   [ ] Codice segue gli standard del progetto
-   [ ] Self-review completato
-   [ ] Documentazione aggiornata se necessario
-   [ ] Nessun warning/error nei tool di qualità

## Issues Correlate

Fixes #123
Related to #456
```

### Review Process

1. **Automated Checks**: CI/CD pipeline passa
2. **Code Review**: Almeno 1 approvazione
3. **Testing**: Feature testata manualmente
4. **Documentation**: Aggiornata se necessario

## Strumenti di Qualità

### PHP CS Fixer

```bash
# Installa
composer require --dev friendsofphp/php-cs-fixer

# Configura .php-cs-fixer.php
<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude(['bootstrap', 'storage', 'vendor'])
            ->name('*.php')
    );

# Esegui
./vendor/bin/php-cs-fixer fix
```

### ESLint & Prettier

```json
// .eslintrc.js
module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'eslint:recommended',
    '@vue/eslint-config-prettier',
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  rules: {
    'vue/multi-word-component-names': 'off',
    'no-console': 'warn',
    'no-debugger': 'error',
  },
}

// .prettierrc
{
  "semi": false,
  "singleQuote": true,
  "tabWidth": 2,
  "trailingComma": "es5"
}
```

### Pre-commit Hooks

```bash
# Installa husky
npm install --save-dev husky

# Setup hooks
npx husky install
npx husky add .husky/pre-commit "composer run cs-fix && npm run lint"
npx husky add .husky/pre-push "php artisan test"
```

## Deployment

### Staging Deployment

```yaml
# .github/workflows/deploy-staging.yml
name: Deploy to Staging

on:
    push:
        branches: [develop]

jobs:
    deploy:
        runs-on: ubuntu-latest
        steps:
            - uses: actions/checkout@v3

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: 8.1

            - name: Install dependencies
              run: |
                  composer install --no-dev --optimize-autoloader
                  npm ci && npm run build

            - name: Deploy to staging
              run: |
                  # Deploy script
```

---

_Ultimo aggiornamento: Ottobre 2024_
_Versione: 1.0_
