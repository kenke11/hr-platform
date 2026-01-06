<?php

namespace App\Http\Controllers;

use App\Models\CandidateApplication;
use Illuminate\Http\Request;

class CandidateApplicationController extends Controller
{
    public function updateStatus(
        CandidateApplication $candidate,
        string $status
    ) {
        abort_unless(
            auth()->user()->hasRoleInCompany('hr'),
            403
        );

        abort_unless(
            in_array($status, ['reviewed', 'shortlisted', 'rejected']),
            422
        );

        $candidate->update([
            'status' => $status,
        ]);

        return back()->with('success', 'Candidate status updated.');
    }
}
