<?php

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function (Company $company) {
            Position::insert([
                [
                    'company_id' => $company->id,
                    'name' => 'CEO',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'company_id' => $company->id,
                    'name' => 'Manager',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'company_id' => $company->id,
                    'name' => 'Employee',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        });
    }
}
