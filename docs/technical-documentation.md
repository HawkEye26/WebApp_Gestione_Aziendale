# Documentazione Tecnica - WebAppGAD

## Architettura dell'Applicazione

### Stack Tecnologico

```
Frontend (Client)
├── Vue.js 3 (Composition API)
├── Inertia.js (SPA-like experience)
├── Tailwind CSS (Styling)
├── Headless UI (Accessible components)
├── Lucide Vue (Icons)
├── DaisyUI (UI components)
├── SweetAlert2 (Modals/Alerts)
└── Vite (Build tool)

Backend (Server)
├── Laravel 11.x
├── PHP 8.1+
├── Laravel Fortify (Authentication)
├── Laravel Jetstream (Team management)
└── Spatie Permissions (Role management)

Database
├── SQLite (Development)
├── MySQL/PostgreSQL (Production)
└── Redis (Cache/Sessions - Optional)
```

### Struttura Directory

```
app/
├── Actions/           # Business logic actions
├── Events/           # Application events
├── Http/
│   ├── Controllers/  # HTTP controllers
│   ├── Middleware/   # HTTP middleware
│   └── Requests/     # Form requests
├── Imports/          # Excel import classes
├── Models/           # Eloquent models
├── Policies/         # Authorization policies
└── Providers/        # Service providers

resources/
├── js/
│   ├── Components/   # Vue components (Jetstream + Custom)
│   ├── components/   # UI components system
│   │   ├── ui/       # Reusable UI components (Card, Alert, etc.)
│   │   └── Icons/    # Icon components
│   ├── Layouts/      # Page layouts
│   ├── Pages/        # Inertia pages
│   ├── utils/        # Utility functions (toast, etc.)
│   └── app.js        # Main JS entry
└── views/            # Blade templates

database/
├── factories/        # Model factories
├── migrations/       # Database migrations
└── seeders/          # Database seeders
```

## Modelli di Dati

### User Model

```php
// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    // Relationships attive
    public function companies(): HasMany

    // Traits utilizzati
    use HasRoles; // Spatie Permission
}
```

**Note**: Il model include trait Jetstream (HasTeams, HasProfilePhoto) ma le relative funzionalità sono disabilitate.

### Company Model

```php
// app/Models/Company.php
class Company extends Model
{
    protected $fillable = [
        'name', 'vat_number', 'address', 'email', 'phone', 'website', 'sector'
    ];

    // Relationships
    public function user(): BelongsTo
}
```

**Note**: Il model include `team_id` nel database ma la relazione team non è utilizzata poiché i team sono disabilitati.

## Laravel Jetstream

### Panoramica

Laravel Jetstream è un pacchetto di scaffolding per applicazioni Laravel che fornisce:

-   **Autenticazione completa**: Login, registrazione, reset password, verifica email
-   **Gestione profili**: Aggiornamento informazioni e cambio password

**Nota**: Le funzionalità avanzate di Jetstream (team, foto profilo, API tokens) sono disabilitate in questa applicazione.

### Configurazione Jetstream

```php
// config/jetstream.php
'stack' => 'inertia',  // Stack utilizzato (inertia con Vue.js)

'middleware' => ['web'],

// Tutte le features sono disabilitate
// 'features' => [
//     Features::teams(['invitations' => true]),
//     Features::accountDeletion(),
// ],
```

### Sistema Ruoli e Permessi

#### Spatie Permission (Ruoli Globali Applicazione)

```php
// database/seeders/RoleSeeder.php
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $role_admin = Role::firstOrCreate(['name' => 'admin']);
        $permission_import_file = Permission::firstOrCreate(['name' => 'import files']);
        $role_admin->syncPermissions([$permission_import_file]);

        $user = User::find(1);
        if ($user) {
            $user->assignRole($role_admin);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
```

**Sistema utilizzato**: Solo Spatie Permission per ruoli globali dell'applicazione. Le funzionalità team di Jetstream sono disabilitate.

## API Endpoints

### Authentication

```http
POST   /login                 # User login
POST   /register              # User registration
POST   /logout                # User logout
POST   /forgot-password       # Password reset request
POST   /reset-password        # Password reset
```

### Companies Management

```http
GET    /Company               # List companies (index)
POST   /Company/store         # Create company
GET    /Company/show/{id}     # Show company details
PUT    /Company/{id}          # Update company
GET    /Company/edit/{id}     # Show edit form
DELETE /Company/destroy/{id}  # Delete company
DELETE /Company/bulk-destroy  # Bulk delete companies

GET    /Company/Create        # Show create form
POST   /companies/import      # Import companies from Excel
GET    /companies/export      # Export companies to Excel
```

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    profile_photo_path VARCHAR(2048) NULL,
    current_team_id BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Companies Table

```sql
CREATE TABLE companies (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    vat_number VARCHAR(50) NULL,
    address TEXT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(50) NULL,
    website VARCHAR(255) NULL,
    sector VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_companies_user_id (user_id)
);
```

**Note**: La tabella include `team_id` per compatibilità futura, ma non è utilizzata attualmente.

### Tabelle Spatie Permission

### Tabelle Spatie Permission

```sql
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY roles_name_guard_name_unique (name, guard_name)
);

CREATE TABLE permissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    guard_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY permissions_name_guard_name_unique (name, guard_name)
);

CREATE TABLE model_has_roles (
    role_id BIGINT NOT NULL,
    model_type VARCHAR(255) NOT NULL,
    model_id BIGINT NOT NULL,

    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (role_id, model_id, model_type),
    INDEX model_has_roles_model_id_model_type_index (model_id, model_type)
);
```

## Configurazione Ambiente

### Variabili Ambiente (.env)

```env
# Application
APP_NAME="WebAppGAD"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Authentication
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=file

# Queue
QUEUE_CONNECTION=sync
```

### Configurazioni Principali

#### config/fortify.php

```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([
        'confirmPassword' => true,
    ]),
],
```

#### config/jetstream.php

```php
'features' => [
    Features::termsAndPrivacyPolicy(),
    Features::profilePhotos(),
    Features::api(),
    Features::teams(['invitations' => true]),
    Features::accountDeletion(),
],
```

## Frontend Architecture

### Vue.js Structure

```javascript
// resources/js/app.js
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
    title: (title) => `${title} - WebAppGAD`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
```

### Component Structure

```vue
<!-- Example: resources/js/Components/CompanyCard.vue -->
<template>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900">
                {{ company.name }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ company.vat_number }}
            </p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    company: {
        type: Object,
        required: true,
    },
});
</script>
```

### Inertia.js Pages

```vue
<!-- Example: resources/js/Pages/Companies/Index.vue -->
<template>
    <AppLayout title="Companies">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Companies
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Content -->
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    companies: Array,
});
</script>
```

### Componenti Jetstream

#### Profile Management

```vue
<!-- resources/js/Pages/Profile/Show.vue -->
<template>
    <AppLayout title="Profile">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <!-- Update Profile Information -->
            <UpdateProfileInformationForm :user="$page.props.auth.user" />

            <!-- Update Password -->
            <UpdatePasswordForm class="mt-10 sm:mt-0" />

            <!-- Logout Other Browser Sessions -->
            <LogoutOtherBrowserSessionsForm :sessions="sessions" />
        </div>
    </AppLayout>
</template>
```

**Note**: I componenti per gestione team, foto profilo e eliminazione account sono disabilitati.

#### Authentication Pages

```vue
<!-- resources/js/Pages/Auth/Login.vue -->
<template>
    <GuestLayout>
        <AuthenticationCard>
            <template #logo>
                <AuthenticationCardLogo />
            </template>

            <div class="mb-4 text-sm text-gray-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ml-2 text-sm text-gray-600"
                            >Remember me</span
                        >
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900"
                    >
                        Forgot your password?
                    </Link>

                    <PrimaryButton
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </GuestLayout>
</template>
```

## Security

### Authentication & Authorization

````php
// Middleware per verificare l'autenticazione
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Routes protette
});

### Authorization

```php
// Policy per autorizzazione (esempio)
class CompanyPolicy
{
    public function view(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->id === $company->user_id;
    }
}
````

**Note**: Autorizzazione basata solo sulla proprietà utente, non su team (disabilitati).

````

### Validazione Input

```php
// app/Http/Requests/CreateCompanyRequest.php
class CreateCompanyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }
}
````

### CSRF Protection

```javascript
// resources/js/app.js
import axios from "axios";

// Configura axios per CSRF
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Token CSRF automatico con Laravel
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}
```

## Performance

### Caching

```php
// Cache delle query
$companies = Cache::remember('user_companies_' . $user->id, 3600, function () use ($user) {
    return $user->companies()->with('team')->get();
});

// Cache delle configurazioni (produzione)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Optimization

```php
// Eager loading per evitare N+1 queries
$companies = Company::with(['user', 'team'])->get();

// Paginazione per grandi dataset
$companies = Company::paginate(15);

// Indexing importante
Schema::table('companies', function (Blueprint $table) {
    $table->index(['user_id', 'created_at']);
    $table->index('vat_number');
});
```

### Asset Optimization

```javascript
// vite.config.js
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ["vue", "@inertiajs/vue3"],
                    ui: ["@headlessui/vue", "lucide-vue-next"],
                    utils: ["sweetalert2", "toastify-js"],
                },
            },
        },
    },
});
```

### Librerie Frontend Utilizzate

#### Component Libraries

-   **@headlessui/vue**: Componenti accessibili senza stile
-   **DaisyUI**: Componenti Tailwind CSS pre-stilizzati
-   **Lucide Vue**: Libreria di icone moderne e pulite
-   **Custom UI System**: Sistema di componenti personalizzato in `/components/ui/`

#### Utilities & Notifications

-   **SweetAlert2**: Modali e alert eleganti
-   **Toastify.js**: Notifiche toast
-   **@mariojgt/wind-notify**: Sistema di notifiche aggiuntivo

#### Data & Interaction

-   **SortableJS**: Drag & drop per liste
-   **Class Variance Authority**: Utility per classi condizionali
-   **Tailwind Merge**: Unione intelligente di classi Tailwind

## Testing

### Feature Tests

```php
// tests/Feature/CompanyTest.php
class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_company(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/companies', [
                'name' => 'Test Company',
                'vat_number' => '12345678901'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
            'user_id' => $user->id
        ]);
    }
}
```

### Unit Tests

```php
// tests/Unit/CompanyModelTest.php
class CompanyModelTest extends TestCase
{
    public function test_company_belongs_to_user(): void
    {
        $company = Company::factory()->create();

        $this->assertInstanceOf(User::class, $company->user);
    }
}
```

## Deployment

### Build Process

```bash
# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

### Docker Setup

```dockerfile
# Dockerfile
FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000
CMD ["php-fpm"]
```

## Monitoring & Logging

### Laravel Logs

```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
    ],

    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
],
```

### Error Tracking

```php
// app/Exceptions/Handler.php
public function report(Throwable $exception): void
{
    if (app()->bound('sentry')) {
        app('sentry')->captureException($exception);
    }

    parent::report($exception);
}
```

## API Reference

### Response Format

```json
{
    "data": {
        "id": 1,
        "name": "Company Name",
        "vat_number": "12345678901",
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "message": "Company created successfully",
    "status": "success"
}
```

### Error Responses

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."]
    },
    "status": "error"
}
```

---

_Ultimo aggiornamento: Ottobre 2024_
_Versione: 1.0_
