<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // ✅ Admin & HR see all companies
        if ($user->canAccessAllCompanies()) {
            return;
        }

        // 🔒 Everyone else sees only own company
        $builder->where(
            $model->getTable() . '.id',
            $user->company_id
        );
    }
}
