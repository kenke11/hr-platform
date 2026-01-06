<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Vacation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    /**
     * Companies list (admin / hr)
     */
    public function index()
    {
        $this->authorize('viewAny', Company::class);

        $companies = Company::latest()->paginate(10);

        return view('companies.index', compact('companies'));
    }

    /**
     * Show create company form
     */
    public function create()
    {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $slug = Str::slug($request->name);

        // ensure unique slug
        $originalSlug = $slug;
        $i = 1;

        while (Company::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i++;
        }

        Company::create([
            'name' => $request->name,
            'slug' => $slug,
            'domain' => $request->domain,
        ]);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Show edit company form
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update company
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update([
            'name' => $request->name,
            'domain' => $request->domain,
        ]);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    /**
     * View company
     */
    public function view(Request $request, Company $company)
    {
        $this->authorize('view', $company);

        $tab = $request->query('tab', 'vacancies');

        if ($tab === 'employees') {
            $employees = User::query()
                ->where('company_id', $company->id)
                ->select(['id', 'name', 'email', 'company_id', 'position_id'])
                ->with(['roles:id,name', 'position:id,name'])
                ->latest()
                ->paginate(10)
                ->withQueryString();

            return view('companies.view', [
                'company' => $company,
                'tab' => 'employees',
                'employees' => $employees,
            ]);
        }

        if ($tab === 'positions') {
            $positions = Position::query()
                ->where('company_id', $company->id)
                ->select(['id', 'name', 'description', 'company_id'])
                ->latest()
                ->paginate(10)
                ->withQueryString();

            return view('companies.view', [
                'company' => $company,
                'tab' => 'positions',
                'positions' => $positions,
            ]);
        }

        if ($tab === 'vacations') {
            $vacations = Vacation::query()
                ->where('company_id', $company->id)
                ->select([
                    'id',
                    'user_id',
                    'start_date',
                    'end_date',
                    'type',
                    'status',
                    'reason',
                    'approved_by',
                    'approved_at',
                    'company_id',
                    'created_at',
                ])
                ->with(['user', 'approver', 'company'])
                ->latest()
                ->paginate(10)
                ->withQueryString();

            return view('companies.view', [
                'company' => $company,
                'tab' => 'vacations',
                'vacations' => $vacations,
            ]);
        }

        // default: vacancies
        $vacancies = Vacancy::query()
            ->where('company_id', $company->id)
            ->select([
                'id',
                'company_id',
                'title',
                'status',
                'employment_type',
                'expiration_date',
                'published_at',
                'created_at',
            ])
            ->with('company:id,name,slug')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('companies.view', [
            'company' => $company,
            'tab' => $tab,
            'vacancies' => $vacancies,
        ]);
    }

    /**
     * Delete company (Admin / HR)
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}
