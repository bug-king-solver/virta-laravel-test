<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase; // This ensures the database is refreshed before each test.

    public function testIndex()
    {
        // Create test data in the database
        $company = Company::factory()->create();

        // Make a GET request to the index endpoint
        $response = $this->get('/api/company');

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains the company data
        $response->assertJson([$company->toArray()]);
    }

    public function testStore()
    {
        // Create test data for the request
        $companyData = [
            'name' => 'Test Company',
        ];

        // Make a POST request to the store endpoint
        $response = $this->post('/api/company', $companyData);

        // Assert that the response status is 201 (Created)
        $response->assertStatus(201);

        // Assert that the response contains the created company data
        $response->assertJson($companyData);
    }

    public function testShow()
    {
        // Create a test company in the database
        $company = Company::factory()->create();

        // Make a GET request to the show endpoint for the created company
        $response = $this->get('/api/company/' . $company->id);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains the company data
        $response->assertJson($company->toArray());
    }

    public function testUpdate()
    {
        // Create a test company in the database
        $company = Company::factory()->create();

        // Data for the update request
        $updatedData = [
            'name' => 'Updated Company Name',
        ];

        // Make a PUT request to the update endpoint for the created company
        $response = $this->put('/api/company/' . $company->id, $updatedData);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains the updated company data
        $response->assertJson($updatedData);

        // Verify that the company record in the database has been updated
        $this->assertDatabaseHas('companies', $updatedData);
    }

    public function testDestroy()
    {
        // Create a test company in the database
        $company = Company::factory()->create();

        // Make a DELETE request to the destroy endpoint for the created company
        $response = $this->delete('/api/company/' . $company->id);

        // Assert that the response status is 204 (No Content)
        $response->assertStatus(204);

        // Verify that the company record has been deleted from the database
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function testChildStations()
    {
        // Create a test company in the database
        $company = Company::factory()->create();

        // Create child companies and stations associated with the main company
        $childCompany = Company::factory()->create([
            'parent_company_id' => $company->id,
            // Assuming you have a 'parent_id' column for relationships
        ]);

        $station = Station::factory()->create([
            'company_id' => $childCompany->id,
            // Assuming 'company_id' is the foreign key
        ]);

        // Make a GET request to the childStations endpoint for the created company
        $response = $this->get('/api/child-stations/' . $company->id);

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the response contains station data related to the company and its children
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'latitude',
                'longitude',
                'company_id',
                'address',
            ],
        ]);
    }

}