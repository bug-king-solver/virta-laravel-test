<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create parent companies
        Company::factory(5)->create();

        // Create child companies with parent_company_id
        Company::factory(10)->create(['parent_company_id' => 1]);
        Company::factory(7)->create(['parent_company_id' => 2]);
        // Add more child companies with different parent_company_id values as needed
    }
}