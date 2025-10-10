# Guida al Testing - WebAppGAD

## Panoramica Testing

WebAppGAD utilizza PHPUnit per i test backend e include test di feature, unit test e test di integrazione per garantire la qualità e stabilità del codice.

## Struttura Test

```
tests/
├── TestCase.php              # Classe base per tutti i test
├── CreatesApplication.php    # Trait per creare l'applicazione di test
├── Feature/                  # Test di funzionalità end-to-end
│   ├── Auth/
│   ├── Companies/
│   └── Teams/
└── Unit/                     # Unit test per singole classi
    ├── Models/
    ├── Services/
    └── Helpers/
```

## Configurazione Test

### Database Test

Il testing utilizza un database separato configurato in `phpunit.xml`:

```xml
<phpunit bootstrap="vendor/autoload.php">
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>

    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
    </php>
</phpunit>
```

### Environment Test

Crea `.env.testing`:

```env
APP_NAME="WebAppGAD Test"
APP_ENV=testing
APP_KEY=base64:test-key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

CACHE_DRIVER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
MAIL_MAILER=array
```

## Tipi di Test

### Feature Tests

I feature test verificano il comportamento end-to-end dell'applicazione:

```php
<?php
// tests/Feature/Companies/CompanyManagementTest.php

namespace Tests\Feature\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_companies_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/companies');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Companies/Index')
        );
    }

    public function test_user_can_create_company(): void
    {
        $user = User::factory()->create();

        $companyData = [
            'name' => 'Test Company',
            'vat_number' => '12345678901',
            'email' => 'test@company.com',
            'address' => 'Via Test 123, Milano',
        ];

        $response = $this->actingAs($user)
            ->post('/companies', $companyData);

        $response->assertRedirect('/companies');

        $this->assertDatabaseHas('companies', [
            'name' => 'Test Company',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_only_view_own_companies(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $company1 = Company::factory()->create(['user_id' => $user1->id]);
        $company2 = Company::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)
            ->get('/companies');

        $response->assertInertia(fn ($page) =>
            $page->has('companies.data', 1)
                 ->has('companies.data.0', fn ($company) =>
                     $company->where('id', $company1->id)
                             ->etc()
                 )
        );
    }

    public function test_company_validation_rules(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/companies', [
                'name' => '', // Nome richiesto
                'email' => 'invalid-email', // Email non valida
            ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_user_can_delete_own_company(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete("/companies/{$company->id}");

        $response->assertRedirect('/companies');
        $this->assertSoftDeleted('companies', ['id' => $company->id]);
    }

    public function test_user_cannot_delete_other_user_company(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)
            ->delete("/companies/{$company->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('companies', ['id' => $company->id]);
    }
}
```

### Unit Tests

Gli unit test verificano singole unità di codice:

```php
<?php
// tests/Unit/Models/CompanyTest.php

namespace Tests\Unit\Models;

use App\Models\Company;
use App\Models\User;
use App\Models\Team;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    public function test_company_belongs_to_user(): void
    {
        $company = new Company;

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $company->user()
        );
    }

    public function test_company_belongs_to_team(): void
    {
        $company = new Company;

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            $company->team()
        );
    }

    public function test_company_has_fillable_attributes(): void
    {
        $company = new Company;

        $expected = [
            'name', 'vat_number', 'address',
            'email', 'phone', 'website', 'sector'
        ];

        $this->assertEquals($expected, $company->getFillable());
    }

    public function test_company_casts_attributes_correctly(): void
    {
        $company = new Company;

        $this->assertArrayHasKey('created_at', $company->getCasts());
        $this->assertArrayHasKey('updated_at', $company->getCasts());
    }
}
```

### Authentication Tests

```php
<?php
// tests/Feature/Auth/AuthenticationTest.php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
```

### Team Management Tests

```php
<?php
// tests/Feature/Teams/TeamManagementTest.php

namespace Tests\Feature\Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_team(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/teams', [
                'name' => 'Test Team',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_invite_team_member(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post("/teams/{$team->id}/members", [
                'email' => 'test@example.com',
                'role' => 'editor',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('team_invitations', [
            'team_id' => $team->id,
            'email' => 'test@example.com',
        ]);
    }

    public function test_team_owner_can_remove_member(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $owner->id]);

        $team->users()->attach($member, ['role' => 'editor']);

        $response = $this->actingAs($owner)
            ->delete("/teams/{$team->id}/members/{$member->id}");

        $response->assertRedirect();

        $this->assertFalse($team->hasUser($member));
    }
}
```

## Test Helpers e Factories

### Factory Definitions

```php
<?php
// database/factories/CompanyFactory.php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'name' => $this->faker->company(),
            'vat_number' => $this->faker->numerify('###########'),
            'address' => $this->faker->address(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'sector' => $this->faker->randomElement([
                'Technology', 'Healthcare', 'Finance',
                'Manufacturing', 'Retail', 'Education'
            ]),
        ];
    }

    public function withUser(User $user): self
    {
        return $this->state(['user_id' => $user->id]);
    }

    public function withTeam(Team $team): self
    {
        return $this->state(['team_id' => $team->id]);
    }
}
```

### Custom Test Traits

```php
<?php
// tests/Traits/InteractsWithTeams.php

namespace Tests\Traits;

use App\Models\Team;
use App\Models\User;

trait InteractsWithTeams
{
    protected function createTeamWithMembers(int $memberCount = 2): Team
    {
        $owner = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $owner->id]);

        $members = User::factory()->count($memberCount)->create();

        foreach ($members as $member) {
            $team->users()->attach($member, ['role' => 'editor']);
        }

        return $team;
    }

    protected function assertUserBelongsToTeam(User $user, Team $team): void
    {
        $this->assertTrue($team->hasUser($user));
    }

    protected function assertUserDoesNotBelongToTeam(User $user, Team $team): void
    {
        $this->assertFalse($team->hasUser($user));
    }
}
```

## Test di Integrazione

### Excel Import Test

```php
<?php
// tests/Feature/Companies/CompanyImportTest.php

namespace Tests\Feature\Companies;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_import_companies_from_excel(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();

        // Crea file Excel di test
        $file = UploadedFile::fake()->create('companies.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->actingAs($user)
            ->post('/companies/import', [
                'file' => $file,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Storage::disk('local')->assertExists('imports/' . $file->hashName());
    }

    public function test_import_validates_file_type(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('invalid.txt', 100, 'text/plain');

        $response = $this->actingAs($user)
            ->post('/companies/import', [
                'file' => $file,
            ]);

        $response->assertSessionHasErrors(['file']);
    }
}
```

### Email Test

```php
<?php
// tests/Feature/Notifications/TeamInvitationTest.php

namespace Tests\Feature\Notifications;

use App\Models\Team;
use App\Models\User;
use App\Notifications\TeamInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TeamInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_invitation_email_is_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post("/teams/{$team->id}/members", [
                'email' => 'test@example.com',
                'role' => 'editor',
            ]);

        Notification::assertSentTo(
            [$user],
            TeamInvitation::class
        );
    }
}
```

## Esecuzione Test

### Comandi Base

```bash
# Esegui tutti i test
php artisan test

# Esegui solo i feature test
php artisan test --testsuite=Feature

# Esegui solo gli unit test
php artisan test --testsuite=Unit

# Esegui test specifici
php artisan test tests/Feature/Companies/CompanyManagementTest.php

# Esegui test con coverage
php artisan test --coverage

# Esegui test con output dettagliato
php artisan test --verbose
```

### Test in Parallel

Per velocizzare l'esecuzione:

```bash
# Installa paratest
composer require --dev brianium/paratest

# Esegui test in parallelo
./vendor/bin/paratest --processes=4
```

### Continuous Integration

Esempio configurazione GitHub Actions:

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
    tests:
        runs-on: ubuntu-latest

        services:
            mysql:
                image: mysql:8.0
                env:
                    MYSQL_ALLOW_EMPTY_PASSWORD: yes
                    MYSQL_DATABASE: webappgad_test
                ports:
                    - 3306:3306
                options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

        steps:
            - uses: actions/checkout@v3

            - name: Setup PHP
              uses: shivammathur/setup-php@v2
              with:
                  php-version: 8.1
                  extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite, bcmath, soap, intl, gd, exif, iconv
                  coverage: xdebug

            - name: Install Composer dependencies
              run: composer install --no-progress --prefer-dist --optimize-autoloader

            - name: Copy environment file
              run: cp .env.example .env

            - name: Generate app key
              run: php artisan key:generate

            - name: Run migrations
              run: php artisan migrate
              env:
                  DB_CONNECTION: mysql
                  DB_HOST: 127.0.0.1
                  DB_PORT: 3306
                  DB_DATABASE: webappgad_test

            - name: Execute tests
              run: php artisan test --coverage-clover coverage.xml

            - name: Upload coverage to Codecov
              uses: codecov/codecov-action@v3
              with:
                  file: ./coverage.xml
```

## Best Practices

### Struttura Test

1. **AAA Pattern**: Arrange, Act, Assert

```php
public function test_example(): void
{
    // Arrange
    $user = User::factory()->create();

    // Act
    $response = $this->actingAs($user)->get('/dashboard');

    // Assert
    $response->assertStatus(200);
}
```

2. **Descriptive Test Names**

```php
// Buono
public function test_user_can_create_company_with_valid_data(): void

// Cattivo
public function test_create_company(): void
```

3. **Un Assert per Test**

```php
// Buono
public function test_company_is_created(): void
{
    // ... setup
    $this->assertDatabaseHas('companies', ['name' => 'Test Company']);
}

public function test_user_is_redirected_after_creating_company(): void
{
    // ... setup
    $response->assertRedirect('/companies');
}
```

### Performance Test

```php
public function test_companies_list_loads_quickly(): void
{
    // Crea molte aziende
    Company::factory()->count(1000)->create();

    $user = User::factory()->create();

    $start = microtime(true);

    $response = $this->actingAs($user)->get('/companies');

    $end = microtime(true);
    $duration = $end - $start;

    $this->assertLessThan(2.0, $duration); // Max 2 secondi
    $response->assertStatus(200);
}
```

### Database Transactions

```php
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions; // Rollback automatico dopo ogni test

    public function test_example(): void
    {
        // Test logic
    }
}
```

---

_Ultimo aggiornamento: Ottobre 2024_
_Versione: 1.0_
