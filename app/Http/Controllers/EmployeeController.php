<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\User;
use App\Models\Company;
use App\Models\Position;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Show create employee form
     */
    public function create(Company $company)
    {
        $user = auth()->user();
        abort_unless($user->canCrudEmployee($company), 403);

        // positions only for this company
        $positions = Position::where('company_id', $company->id)->get();

        // possible managers (same company)
        $managers = User::where('company_id', $company->id)->get();

        return view('employees.create', compact(
            'company',
            'positions',
            'managers'
        ));
    }

    /**
     * Store employee
     */
    public function store(StoreEmployeeRequest $request, Company $company)
    {
        $employee = User::create([
            'company_id' => $request->company_id,
            'name'       => $request->name,
            'email'      => $request->email,
            'position_id'=> $request->position_id,
            'manager_id' => $request->manager_id,
            'password'   => Hash::make('password'),
        ]);

        $employee->assignRoleForCompany('employee');

        return redirect()
            ->route('companies.view', $employee->company)
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Show edit employee form
     */
    public function edit(Company $company, User $user)
    {
        $authUser = auth()->user();
        abort_unless($authUser->canCrudEmployee($company), 403);

        $positions = Position::where('company_id', $company->id)->get();

        $managers = User::where('company_id', $company->id)
            ->where('id', '!=', $user->id)
            ->get();

        return view('employees.edit', [
            'company'   => $company,
            'employee'  => $user,
            'positions' => $positions,
            'managers'  => $managers,
        ]);
    }

    /**
     * Update employee
     */
    public function update(UpdateEmployeeRequest $request, Company $company, User $user)
    {
        $user->update($request->validated());

        return redirect()
            ->route('employees.show', [$company, $user])
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Show employee profile
     */
    public function show(Company $company, User $user)
    {
        $authUser = auth()->user();
        abort_unless($authUser->canCrudEmployee($company), 403);

        return view('employees.show', [
            'company'  => $company,
            'employee' => $user->load([
                'position:id,name',
                'manager:id,name',
            ]),
        ]);
    }

    /**
     * Delete employee
     */
    public function destroy(Company $company, User $user)
    {
        $authUser = auth()->user();
        abort_unless($authUser->canCrudEmployee($company), 403);

        User::where('manager_id', $user->id)
            ->update(['manager_id' => null]);

        $user->delete();

        return redirect()
            ->route('companies.view', $company)
            ->with('success', 'Employee deleted successfully.');
    }
}
