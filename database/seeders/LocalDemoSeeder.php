<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class LocalDemoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Companies
        |--------------------------------------------------------------------------
        */

        $demoCompany = Company::factory()->create([
            'name' => 'Demo Company',
            'slug' => 'demo',
        ]);

        $secondCompany = Company::factory()->create([
            'name' => 'Second Company',
            'slug' => 'second',
        ]);

        /*
        |--------------------------------------------------------------------------
        | System-level users (NO company_id)
        |--------------------------------------------------------------------------
        */

        // 👑 Admin (system)
        $admin = User::factory()->create([
            'company_id' => null,
            'email' => 'admin@app.com',
            'name' => 'System Admin',
        ]);

        $admin->assignRoleForCompany('admin');

        // 🧑‍💼 HR (system)
        $hr = User::factory()->create([
            'company_id' => null,
            'email' => 'hr@app.com',
            'name' => 'System HR',
        ]);

        $hr->assignRoleForCompany('hr');

        /*
        |--------------------------------------------------------------------------
        | Company Admins (tenant-level)
        |--------------------------------------------------------------------------
        */

        $companyAdmin1 = User::factory()->create([
            'company_id' => $demoCompany->id,
            'email' => 'admin@demo.com',
            'name' => 'Demo Company Admin',
        ]);

        $companyAdmin1->assignRoleForCompany('company-admin');

        $companyAdmin2 = User::factory()->create([
            'company_id' => $secondCompany->id,
            'email' => 'admin@second.com',
            'name' => 'Second Company Admin',
        ]);

        $companyAdmin2->assignRoleForCompany('company-admin');

        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        User::factory(5)->create([
            'company_id' => $demoCompany->id,
        ])->each(fn (User $user) =>
        $user->assignRoleForCompany('employee')
        );

        User::factory(3)->create([
            'company_id' => $secondCompany->id,
        ])->each(fn (User $user) =>
        $user->assignRoleForCompany('employee')
        );
    }
}
