<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;

class VacancyPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRoleInCompany('hr');
    }

    /**
     * List vacancies (HR + admin)
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRoleInCompany('hr')
            || $user->hasRoleInCompany('admin');
    }

    /**
     * Company Admin → own company vacancies
     */
    public function viewCompanyVacancies(User $user): bool
    {
        return $user->hasRoleInCompany('admin')
            || $user->hasRoleInCompany('hr')
            || $user->hasRoleInCompany('company-admin');
    }

    /**
     * View single vacancy (same rules)
     */
    public function view(User $user, Vacancy $vacancy): bool
    {
        return $this->viewAny($user)
            && $vacancy->company_id === $user->company_id;
    }

    /**
     * HR only
     */
    public function update(User $user): bool
    {
        return $user->hasRoleInCompany('hr');
    }

    /**
     * HR only
     */
    public function delete(User $user, Vacancy $vacancy): bool
    {
        return $user->hasRoleInCompany('hr')
            || $user->hasRoleInCompany('admin')
            || $user->hasRoleInCompany('company-admin');
    }
}
