<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVacationRequest;
use App\Models\Attendance;
use App\Models\Vacation;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class VacationController extends Controller
{
    public function create()
    {
        return view('vacations.create');
    }


    public function store(StoreVacationRequest $request)
    {
        $user = auth()->user();

        Vacation::create([
            'company_id' => $user->company_id,
            'user_id'    => $user->id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'type'       => $request->type,
            'reason'     => $request->reason,
            'status'     => 'pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Vacation request submitted and waiting for approval.');
    }

    /**
     * Approve vacation request
     */
    public function approve(Vacation $vacation)
    {
        abort_if($vacation->status !== 'pending', 422, 'Vacation already processed.');

        $vacation->update([
            'status'       => 'approved',
            'approved_by'  => auth()->id(),
            'approved_at'  => now(),
        ]);

        $period = CarbonPeriod::create(
            $vacation->start_date,
            $vacation->end_date
        );

        foreach ($period as $date) {
            Attendance::updateOrCreate(
                [
                    'user_id' => $vacation->user_id,
                    'date'    => $date->toDateString(),
                ],
                [
                    'company_id'      => $vacation->company_id,
                    'is_absent'       => true,
                    'absence_reason' => $vacation->type,
                    'check_in_at'     => null,
                    'check_out_at'    => null,
                ]
            );
        }

        return back()->with('success', 'Vacation approved.');
    }

    /**
     * Reject vacation request
     */
    public function reject(Vacation $vacation)
    {
        abort_if($vacation->status !== 'pending', 422, 'Vacation already processed.');

        $vacation->update([
            'status'       => 'rejected',
            'approved_by'  => auth()->id(),
            'approved_at'  => now(),
        ]);

        return back()->with('success', 'Vacation rejected.');
    }
}
