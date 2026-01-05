<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Company;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Show create form
     */
    public function create(Company $company)
    {
        abort_unless(auth()->user()->canCrudPositions($company), 403);

        return view('positions.create', compact('company'));
    }

    /**
     * Store position
     */
    public function store(StorePositionRequest $request, Company $company)
    {
        $company->positions()->create($request->validated());

        return redirect()
            ->route('companies.view', $company)
            ->with('success', 'Position created.');
    }

    /**
     * Edit position
     */
    public function edit(Company $company, Position $position)
    {
        abort_unless(auth()->user()->canCrudPositions($company), 403);
        abort_if($position->company_id !== $company->id, 404);

        return view('positions.edit', compact('company', 'position'));
    }

    /**
     * Update position
     */
    public function update(UpdatePositionRequest $request, Company $company, Position $position)
    {
        abort_unless(auth()->user()->canCrudPositions($company), 403);
        abort_if($position->company_id !== $company->id, 404);

        $position->update($request->validated());

        return redirect()
            ->route('companies.view', $company)
            ->with('success', 'Position updated.');
    }

    /**
     * Delete position
     */
    public function destroy(Company $company, Position $position)
    {
        abort_unless(auth()->user()->canCrudPositions($company), 403);
        abort_if($position->company_id !== $company->id, 404);

        // detach employees
        $position->users()->update(['position_id' => null]);

        $position->delete();

        return redirect()
            ->route('companies.view', $company)
            ->with('success', 'Position deleted.');
    }
}
