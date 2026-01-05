<?php

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use App\Models\User;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'company_id' => null,
            'position_id' => null,
            'email' => 'admin@app.com',
            'name' => 'System Admin',
        ]);
        $admin->assignRoleForCompany('admin');

        $hr = User::factory()->create([
            'company_id' => null,
            'position_id' => null,
            'email' => 'hr@app.com',
            'name' => 'System HR',
        ]);
        $hr->assignRoleForCompany('hr');
    }
}
