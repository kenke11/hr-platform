<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class EmployeePolicy
{
    /**
     * Create employee
     */
    public function create(User $authUser, Company $company): bool
    {
        return $authUser->canCrudEmployee($company);
    }

    /**
     * View employee
     */
    public function view(User $authUser, Company $company): bool
    {
        return $authUser->canCrudEmployee($company);
    }

    /**
     * Update employee
     */
    public function update(User $authUser, Company $company): bool
    {
        return $authUser->canCrudEmployee($company);
    }

    /**
     * Delete employee
     */
    public function delete(User $authUser, Company $company): bool
    {
        return $authUser->canCrudEmployee($company);
    }
}
