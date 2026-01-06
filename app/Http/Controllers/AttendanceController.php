<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkAbsentRequest;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(AttendanceService $service)
    {
        try {
            $service->checkIn(auth()->user());

            return back()->with('success', 'Checked in successfully.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function checkOut(AttendanceService $service)
    {
        try {
            $service->checkOut(auth()->user());

            return back()->with('success', 'Checked out successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }

    public function markAbsent(MarkAbsentRequest $request, Company $company, User $user)
    {
        abort_if($user->company_id !== $company->id, 404);
        abort_unless(auth()->user()->canCrudEmployee($company), 403);

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'date'    => now(),
            ],
            [
                'company_id'      => $company->id,
                'is_absent'       => true,
                'absence_reason' => $data['reason'] ?? null,
                'check_in_at'     => null,
                'check_out_at'    => null,
            ]
        );

        return back()->with('success', 'Employee marked as absent.');
    }
}
