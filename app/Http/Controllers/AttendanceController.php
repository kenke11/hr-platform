<?php

namespace App\Http\Controllers;

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
}
