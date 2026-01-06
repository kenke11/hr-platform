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
                    $date = Carbon::now()->subDays($i);

                    // skip weekends
                    if ($date->isWeekend()) {
                        continue;
                    }

                    // random scenario
                    $rand = rand(1, 100);

                    // 🟥 ABSENT (10%)
                    if ($rand <= 10) {
                        Attendance::updateOrCreate(
                            [
                                'user_id' => $employee->id,
                                'date'    => $date->toDateString(),
                            ],
                            [
                                'company_id'      => $company->id,
                                'is_absent'       => true,
                                'absence_reason' => collect(['sick', 'vacation', 'personal'])->random(),
                                'check_in_at'     => null,
                                'check_out_at'    => null,
                            ]
                        );

                        continue;
                    }

                    // 🟨 CHECK-IN only (20%)
                    if ($rand <= 30) {
                        $checkIn = $date->copy()->setTime(
                            rand(8, 10),
                            rand(0, 59)
                        );

                        Attendance::updateOrCreate(
                            [
                                'user_id' => $employee->id,
                                'date'    => $date->toDateString(),
                            ],
                            [
                                'company_id'   => $company->id,
                                'is_absent'    => false,
                                'check_in_at'  => $checkIn,
                                'check_out_at' => null,
                            ]
                        );

                        continue;
                    }

                    // 🟩 FULL DAY (70%)
                    $checkIn = $date->copy()->setTime(
                        rand(8, 10),
                        rand(0, 59)
                    );

                    $checkOut = (clone $checkIn)
                        ->addHours(rand(7, 9))
                        ->addMinutes(rand(0, 30));

                    Attendance::updateOrCreate(
                        [
                            'user_id' => $employee->id,
                            'date'    => $date->toDateString(),
                        ],
                        [
                            'company_id'   => $company->id,
                            'is_absent'    => false,
                            'check_in_at'  => $checkIn,
                            'check_out_at' => $checkOut,
                        ]
                    );
                }
            }
        });
    }
}
