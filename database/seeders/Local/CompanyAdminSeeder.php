<?php

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Position;

class CompanyAdminSeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function (Company $company) {

            $ceo = Position::where('company_id', $company->id)
                ->where('name', 'CEO')
                ->first();

            $admin = User::factory()->create([
                'company_id' => $company->id,
                'position_id' => $ceo?->id,
                'manager_id' => null,
                'email' => 'admin@' . $company->slug . '.com',
                'name' => $company->name . ' Admin',
            ]);

            $admin->assignRoleForCompany('company-admin');
        });
    }
}
