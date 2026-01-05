<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Guard for Spatie roles
     */
    protected string $guard_name = 'sanctum';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'manager_id',
        'position_id',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ==========================================================
     | Relationships
     |========================================================== */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /* =======================
     | Hierarchy
     |======================= */

    // Manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Subordinates
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /* ==========================================================
     | Role helpers (multi-tenant aware)
     |========================================================== */

    /**
     * Assign role scoped to company.
     * For admin/hr company_id may be NULL.
     */
    public function assignRoleForCompany(string $roleName, ?int $companyId = null): void
    {
        // admin / hr can be system-level
        if (in_array($roleName, ['admin', 'hr'], true)) {
            $companyId = null;
        } else {
            $companyId ??= $this->company_id;
        }

        $role = Role::where('name', $roleName)
            ->where('guard_name', $this->guard_name)
            ->firstOrFail();

        $this->roles()->syncWithoutDetaching([
            $role->id => ['company_id' => $companyId],
        ]);
    }

    /**
     * Check role inside company.
     * For admin/hr company_id is ignored.
     */
    public function hasRoleInCompany(string $roleName, ?int $companyId = null): bool
    {
        // system-level roles
        if (in_array($roleName, ['admin', 'hr'], true)) {
            return $this->roles()
                ->whereNull('model_has_roles.company_id')
                ->where('name', $roleName)
                ->exists();
        }

        $companyId ??= $this->company_id;

        return $this->roles()
            ->wherePivot('company_id', $companyId)
            ->where('name', $roleName)
            ->exists();
    }

    /**
     * Check any role in company or system-level
     */
    public function hasAnyRoleInCompany(array $roles, ?int $companyId = null): bool
    {
        // system-level roles
        if (array_intersect($roles, ['admin', 'hr'])) {
            return $this->roles()
                ->whereNull('model_has_roles.company_id')
                ->whereIn('name', $roles)
                ->exists();
        }

        $companyId ??= $this->company_id;

        return $this->roles()
            ->wherePivot('company_id', $companyId)
            ->whereIn('name', $roles)
            ->exists();
    }

    /* ==========================================================
     | Permissions
     |========================================================== */

    public function hasPermissionInCompany(string $permission, ?int $companyId = null): bool
    {
        // admin / hr = system permissions
        if ($this->canAccessAllCompanies()) {
            return $this->permissions()
                ->whereNull('model_has_permissions.company_id')
                ->where('name', $permission)
                ->exists();
        }

        $companyId ??= $this->company_id;

        return $this->permissions()
            ->wherePivot('company_id', $companyId)
            ->where('name', $permission)
            ->exists();
    }

    public function canCrudEmployee(?Company $company = null): bool
    {
        // system-level
        if (
            $this->hasRoleInCompany('admin') ||
            $this->hasRoleInCompany('hr')
        ) {
            return true;
        }

        // company-admin only inside own company
        return $company
            && $this->hasRoleInCompany('company-admin')
            && $this->company_id === $company->id;
    }

    public function canCrudPositions(?Company $company = null)
    {
        // system-level
        if (
            $this->hasRoleInCompany('admin') ||
            $this->hasRoleInCompany('hr')
        ) {
            return true;
        }

        // company-admin only inside own company
        return $company
            && $this->hasRoleInCompany('company-admin')
            && $this->company_id === $company->id;
    }

    /**
     * System-level access
     */
    public function canAccessAllCompanies(): bool
    {
        return $this->roles()
            ->whereNull('model_has_roles.company_id')
            ->whereIn('name', ['admin', 'hr'])
            ->exists();
    }

    /* ==========================================================
     | Helpers & Scopes
     |========================================================== */

    /**
     * User belongs to company (not system user)
     */
    public function belongsToCompany(): bool
    {
        return !is_null($this->company_id);
    }

    /**
     * Scope users by company
     */
    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
