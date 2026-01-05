<?php

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Position;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function (Company $company) {
            $positionManager = Position::where('company_id', $company->id)
                ->where('name', 'Manager')
                ->firstOrFail();

            $manager = User::where('company_id', $company->id)
                ->where('position_id', $positionManager->id)
                ->first();

            if(!$manager) {

                $manager = User::factory()->create([
                    'company_id' => $company->id,
                    'position_id' => $positionManager?->id,
                ]);

                $manager->assignRoleForCompany('employee');
            }

            $position = Position::where('company_id', $company->id)
                ->where('name', 'Employee')
                ->first();

            User::factory(5)->create([
                'company_id' => $company->id,
                'position_id' => $position?->id,
                'manager_id' => $manager?->id,
            ])->each(fn (User $user) =>
            $user->assignRoleForCompany('employee')
            );
        });
    }
}
