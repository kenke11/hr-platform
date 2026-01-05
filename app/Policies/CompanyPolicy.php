<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    /**
     * View companies list
     */
    public function viewAny(User $user): bool
    {
        return $user->canAccessAllCompanies();
    }

    /**
     * View single company
     */
    public function view(User $user): bool
    {
        return $user->canAccessAllCompanies();
    }

    /**
     * Create company
     */
    public function create(User $user): bool
    {
        return $user->canAccessAllCompanies();
    }

    /**
     * Update company
     */
    public function update(User $user): bool
    {
        return $user->canAccessAllCompanies();
    }

    /**
     * Delete company
     */
    public function delete(User $user): bool
    {
        return $user->canAccessAllCompanies();
    }
}
