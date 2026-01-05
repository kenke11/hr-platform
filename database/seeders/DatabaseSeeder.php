<?php

namespace Database\Seeders;

use Database\Seeders\Local\AttendanceSeeder;
use Database\Seeders\Local\CompanyAdminSeeder;
use Database\Seeders\Local\CompanySeeder;
use Database\Seeders\Local\EmployeeSeeder;
use Database\Seeders\Local\PositionSeeder;
use Database\Seeders\Local\SystemUserSeeder;
use Database\Seeders\Local\VacancySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        if (app()->environment('local')) {
            $this->call([
                CompanySeeder::class,
                PositionSeeder::class,
                SystemUserSeeder::class,
                CompanyAdminSeeder::class,
                EmployeeSeeder::class,
                VacancySeeder::class,
                AttendanceSeeder::class,
            ]);
        }
    }
}
