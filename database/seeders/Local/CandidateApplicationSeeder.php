<?php

namespace Database\Seeders\Local;

use App\Models\CandidateApplication;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

class CandidateApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vacancy::query()
            ->where('status', 'published')
            ->each(function (Vacancy $vacancy) {

                $count = rand(3, 10);

                CandidateApplication::factory($count)->create([
                    'company_id' => $vacancy->company_id,
                    'vacancy_id' => $vacancy->id,
                ]);
            });
    }
}
