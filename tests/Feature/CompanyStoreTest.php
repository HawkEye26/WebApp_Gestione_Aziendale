<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyStoreTest extends TestCase
{
    // Importante: pulisce il database tra i test
    use RefreshDatabase;

    // Test per la creazione di una nuova azienda
    public function test_can_add_the_company(): void
    {
        // Crea un utente per l'autenticazione (questo va nel database per la sessione)
        /** @var User $user */
        $user = User::factory()->create();

        // Dati della company che verranno inviati come se fossero dal form
        $data = [
            'company_name' => 'Azienda Agricola Fabbri',
            'address' => 'Via Pisacaro 456',
            'zip_code' => '98121',
            'city' => 'Messina',
            'province' => 'ME',
            'region' => 'Sicilia',
            'email' => 'FabbriAgricola@gogle.com',
        ];

        // Simula l'invio del form da parte dell'utente autenticato
        $res = $this->actingAs($user)->post('/Company/store', $data);

        // Controlla che ritorni a /Company
        $res->assertRedirect('/Company');

        // Verifica nel database se è stata salvata
        $this->assertDatabaseHas('companies', [
            'company_name' => 'Azienda Agricola Fabbri',
            'address' => 'Via Pisacaro 456',
            'zip_code' => '98121',
            'city' => 'Messina',
            'province' => 'ME',
            'region' => 'Sicilia',
            'email' => 'FabbriAgricola@gogle.com',
        ]);
    }


    // Test per leggere un'azienda
    public function test_it_reads_a_company()
    {
        /** @var User $user */
        $user = User::factory()->create();

        // Crea un'azienda nel database
        $company = Company::factory()->create([
            'company_name' => 'Azienda Test SRL',
            'address' => 'Via Roma 123',
            'zip_code' => '20019',
            'city' => 'Milano',
            'province' => 'MI',
            'region' => 'Lombardia',
            'email' => 'AziendaTest@google.com'

        ]);

        // Simula la richiesta autenticata per visualizzare l'azienda
        $response = $this->actingAs($user)->get("/Company/show/{$company->id}");

        // Controlla se la pagina esiste
        $response->assertStatus(200);

        // Verifica che la pagina contenga il nome dell'azienda
        $response->assertSee($company->company_name);
    }

    // Test per aggiornare un'azienda
    public function test_can_update_a_company(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        // Crea un'azienda nel database
        $company = Company::factory()->create([
            'company_name' => 'Azienda Test SRL',
            'address' => 'Via Roma 123',
            'zip_code' => '20019',
            'city' => 'Milano',
            'province' => 'MI',
            'region' => 'Lombardia',
            'email' => 'AziendaTest@google.com'
        ]);

        // Dati aggiornati
        $updatedData = [
            'company_name' => 'Azienda Aggiornata SRL',
            'address' => 'Via Nuova 789',
            'zip_code' => '00100',
            'city' => 'Roma',
            'province' => 'RM',
            'region' => 'Lazio',
            'email' => 'info@aziendaaggiornata.it',
        ];

        // Simula l'aggiornamento
        $response = $this->actingAs($user)->put("/Company/{$company->id}", $updatedData);

        $response->assertRedirect('/Company');

        // Verifica che i dati siano stati aggiornati nel database
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => 'Azienda Aggiornata SRL',
            'address' => 'Via Nuova 789',
            'zip_code' => '00100',
            'city' => 'Roma',
            'province' => 'RM',
            'region' => 'Lazio',
            'email' => 'info@aziendaaggiornata.it',
        ]);
    }

    // Test per cancellare un'azienda
    public function test_can_delete_a_company(): void
    {
        /** @var User $user */
        $user = User::factory()->create();


        $company = Company::factory()->create([
            'company_name' => 'Azienda Test SRL',
            'address' => 'Via Roma 123',
            'zip_code' => '20019',
            'city' => 'Milano',
            'province' => 'MI',
            'region' => 'Lombardia',
            'email' => 'AziendaTest@google.com'
        ]);

        // Simula la cancellazione
        $response = $this->actingAs($user)->delete("/Company/destroy/{$company->id}");

        // Riporta alla homepage perchè nel controller utilizza back() e quindi se non sa dove ritornare va alla homepage
        $response->assertRedirect('/');

        // Verifica che l'azienda sia stata rimossa dal database
        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
            'company_name' => 'Azienda Test SRL',
            'address' => 'Via Roma 123',
            'zip_code' => '20019',
            'city' => 'Milano',
            'province' => 'MI',
            'region' => 'Lombardia',
            'email' => 'AziendaTest@google.com'
        ]);
    }
}
