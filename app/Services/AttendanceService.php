<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    public function checkIn(User $user): Attendance
    {
        $today = now()->toDateString();

        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'company_id' => $user->company_id,
            ]
        );

        if ($attendance->check_in_at) {
            throw new \Exception('Already checked in today.');
        }

        $attendance->update([
            'check_in_at' => now(),
        ]);

        return $attendance;
    }

    public function checkOut(User $user): Attendance
    {
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', now()->toDateString())
            ->first();

        if (! $attendance || ! $attendance->check_in_at) {
            throw new \Exception('Check-in required before check-out.');
        }

        if ($attendance->check_out_at) {
            throw new \Exception('Already checked out today.');
        }

        $attendance->update([
            'check_out_at' => now(),
        ]);

        return $attendance;
    }
}
