<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Create some sample stations in the database.
        Station::factory(5)->create();

        // Send a GET request to the index endpoint.
        $response = $this->get('/api/station');

        // Assert that the response has a 200 status code and JSON content.
        $response->assertStatus(200)
            ->assertJsonStructure([
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

    public function testStore()
    {
        $company = Company::factory()->create();

        // Create sample data for a new station.
        $data = [
            'name' => 'Test Station',
            'latitude' => 4,
            'longitude' => 7,
            'address' => '123 Test Street',
            'company_id' => $company->id,
        ];

        // Send a POST request to the store endpoint with the sample data.
        $response = $this->post('/api/station', $data);

        // Assert that the response has a 201 status code and JSON content.
        $response->assertStatus(201)
            ->assertJson($data);
    }

    public function testShow()
    {
        // Create a sample station in the database.
        $station = Station::factory()->create();

        // Send a GET request to the show endpoint with the station's ID.
        $response = $this->get('/api/station/' . $station->id);

        // Assert that the response has a 200 status code and JSON content.
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'latitude',
                'longitude',
                'company_id',
                'address',
            ]);
    }

    public function testUpdate()
    {
        // Create a sample station in the database.
        $company = Company::factory()->create();
        $station = Station::factory()->create();

        // Create updated data for the station.
        $updatedData = [
            'name' => 'Updated Station Name',
            'latitude' => 50.98765,
            'longitude' => -80.12345,
            'company_id' => $company->id,
            'address' => '456 Updated Street',
        ];

        // Send a PUT request to the update endpoint with the updated data.
        $response = $this->put('/api/station/' . $station->id, $updatedData);

        // Assert that the response has a 200 status code and JSON content.
        $response->assertStatus(200)
            ->assertJson($updatedData);
    }

    public function testDestroy()
    {
        // Create a sample station in the database.
        $station = Station::factory()->create();

        // Send a DELETE request to the destroy endpoint with the station's ID.
        $response = $this->delete('/api/station/' . $station->id);

        // Assert that the response has a 204 status code (no content).
        $response->assertStatus(204);

        // Ensure the station has been deleted from the database.
        $this->assertDatabaseMissing('stations', ['id' => $station->id]);
    }

    public function testStationsWithinRadius()
    {
        // Create some sample companies.
        $company1 = Company::factory()->create();
        $company2 = Company::factory()->create();
        $company3 = Company::factory()->create();

        // Create some sample stations with specific latitude, longitude, and company_id values.
        Station::factory()->create([
            'name' => 'Station 1',
            'latitude' => 40.0,
            'longitude' => -75.0,
            'company_id' => $company1->id,
            'address' => '123 Station 1 Street',
        ]);

        Station::factory()->create([
            'name' => 'Station 2',
            'latitude' => 41.0,
            'longitude' => -76.0,
            'company_id' => $company1->id,
            'address' => '456 Station 2 Street',
        ]);

        Station::factory()->create([
            'name' => 'Station 3',
            'latitude' => 42.0,
            'longitude' => -77.0,
            'company_id' => $company1->id,
            'address' => '789 Station 3 Street',
        ]);

        // Send a GET request to the stationsWithinRadius endpoint with specific parameters.
        $response = $this->get('/api/stations-within-radius?latitude=40.5&longitude=-75.5&radius=10000');

        // Assert that the response has a 200 status code.
        $response->assertStatus(200);

        // Assert the JSON structure of the response.
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'latitude',
                'longitude',
                'company_id',
                'address',
                'distance',
            ],
        ]);
    }
}