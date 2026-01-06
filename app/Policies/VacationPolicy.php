<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacation;

class VacationPolicy
{
    /**
     * Create vacation request (employee)
     */
    public function create(User $user): bool
    {
        return $user->belongsToCompany();
    }

    /**
     * Approve vacation
     */
    public function approve(User $user, Vacation $vacation): bool
    {
        return
            $vacation->status === 'pending'
            && $user->canApproveOrRejectVacation($vacation->company);
    }

    /**
     * Reject vacation
     */
    public function reject(User $user, Vacation $vacation): bool
    {
        return
            $vacation->status === 'pending'
            && $user->canApproveOrRejectVacation($vacation->company);
    }
}
