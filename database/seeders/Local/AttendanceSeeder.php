<?php

namespace Database\Seeders\Local;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        Company::all()->each(function (Company $company) {

            $employees = User::where('company_id', $company->id)->get();

            foreach ($employees as $employee) {

                // last 5 working days
                for ($i = 0; $i < 5; $i++) {
                    $date = Carbon::now()->subDays($i)->toDateString();

                    // skip weekends (optional)
                    if (Carbon::parse($date)->isWeekend()) {
                        continue;
                    }

                    $checkIn = Carbon::parse($date)->setTime(
                        rand(8, 10),
                        rand(0, 59)
                    );

                    // 70% chance of checkout
                    $hasCheckout = rand(1, 100) <= 70;

                    $checkOut = $hasCheckout
                        ? (clone $checkIn)->addHours(rand(7, 9))->addMinutes(rand(0, 30))
                        : null;

                    Attendance::updateOrCreate(
                        [
                            'user_id' => $employee->id,
                            'date'    => $date,
                        ],
                        [
                            'company_id'   => $company->id,
                            'check_in_at'  => $checkIn,
                            'check_out_at' => $checkOut,
                        ]
                    );
                }
            }
        });
    }
}
