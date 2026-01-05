<?php

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::factory()->create([
            'name' => 'Demo Company',
            'slug' => 'demo',
        ]);

        Company::factory()->create([
            'name' => 'Second Company',
            'slug' => 'second',
        ]);
    }
}
