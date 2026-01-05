<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $user->load([
            'company:id,name,slug',
            'position:id,name',
            'manager:id,name',
        ]);

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', now())
            ->first();

        $attendances = $user->attendances()
            ->orderByDesc('date')
            ->paginate(10, ['*'], 'attendance_page')
            ->withQueryString();

        $subordinates = $user->subordinates()
            ->with('position:id,name')
            ->orderBy('name')
            ->paginate(10, ['*'], 'subordinates_page')
            ->withQueryString();

        return view('profile.index', [
            'user'         => $user,
            'todayAttendance'  => $todayAttendance,
            'attendances'  => $attendances,
            'subordinates' => $subordinates,
        ]);
    }
}
