<?php

namespace Database\Seeders\Local;

use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function (Company $company) {

            // Draft vacancies
            Vacancy::factory(2)->create([
                'company_id' => $company->id,
            ]);

            // Published vacancies
            Vacancy::factory(3)
                ->published()
                ->withExpiration()
                ->create([
                    'company_id' => $company->id,
                ]);

            // Expired vacancies
            Vacancy::factory(1)
                ->expired()
                ->create([
                    'company_id' => $company->id,
                ]);
        });
    }
}
