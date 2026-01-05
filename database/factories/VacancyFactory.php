<?php

namespace Database\Factories;

use App\Models\Vacancy;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->city(),
            'employment_type' => $this->faker->randomElement([
                'full_time',
                'part_time',
                'contract',
            ]),
            'status' => 'draft',
            'published_at' => null,
            'expiration_date' => null,
            'is_active' => true,
        ];
    }

    /* =======================
     | States
     |======================= */

    public function published(): self
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function expired(): self
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => now()->subDays(14),
            'expiration_date' => now()->subDay(),
        ]);
    }

    public function withExpiration(): self
    {
        return $this->state(fn () => [
            'expiration_date' => now()->addDays(30),
        ]);
    }
}
