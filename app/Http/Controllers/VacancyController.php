<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyVacancyIndexRequest;
use App\Http\Requests\CreateVacancyRequest;
use App\Http\Requests\IndexVacancyRequest;
use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VacancyController extends Controller
{
    use AuthorizesRequests;


    /**
     * List vacancies (HR / Admin)
     */
    public function index(IndexVacancyRequest $request)
    {
        $query = Vacancy::query()->with('company');

        // Filter by status (ONLY if provided)
        if ($request->filled('status')) {
            match ($request->status) {
                'published' => $query->published(),
                'draft'     => $query->draft(),
                default     => null,
            };
        }

        // Filter expired (ONLY if provided)
        if ($request->boolean('expired')) {
            $query->whereNotNull('expiration_date')
                ->where('expiration_date', '<', now()->toDateString());
        }

        $vacancies = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vacancies.index', compact('vacancies'));
    }

    /**
     * Company Admin — vacancies for one company (by slug)
     */
    public function companyIndex(CompanyVacancyIndexRequest $request, Company $company)
    {
        $query = Vacancy::query()
            ->where('company_id', $company->id)
            ->with('company');

        if (! $request->hasAny(['status', 'expired'])) {
            $query->where(function ($q) {
                $q->whereNull('expiration_date')
                    ->orWhere('expiration_date', '>=', now()->toDateString());
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'published' => $query->published(),
                'draft'     => $query->draft(),
            };
        }

        if ($request->boolean('expired')) {
            $query->whereNotNull('expiration_date')
                ->where('expiration_date', '<', now()->toDateString());
        }

        $vacancies = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vacancies.index', compact('vacancies', 'company'));
    }

    /**
     * Show create vacancy form (HR only)
     */
    public function create(CreateVacancyRequest $request)
    {
        $companies = Company::orderBy('name')->get();

        $selectedCompany = null;

        if ($request->filled('company')) {
            $selectedCompany = Company::where('slug', $request->company)->firstOrFail();
        }

        return view('vacancies.create', [
            'companies' => $companies,
            'selectedCompany' => $selectedCompany,
        ]);
    }

    /**
     * Store vacancy
     */
    public function store(StoreVacancyRequest $request)
    {
        $vacancy = Vacancy::create([
            'company_id'       => $request->company_id,
            'title'            => $request->title,
            'description'      => $request->description,
            'location'         => $request->location,
            'employment_type'  => $request->employment_type,
            'expiration_date'  => $request->expiration_date,
            'status'           => 'draft',
            'published_at'     => null,
            'is_active'        => true,
        ]);

        if ($request->action === 'published') {
            $vacancy->publish();
        }

        $company = Company::find($request->company_id);

        return redirect()
            ->route('vacancies.company', $company->slug)
            ->with('success', 'Vacancy created successfully.');
    }

    /**
     * Show edit form (HR only)
     */
    public function edit(Vacancy $vacancy)
    {
        return view('vacancies.edit', compact('vacancy'));
    }

    /**
     * Update vacancy
     */
    public function update(UpdateVacancyRequest $request, Vacancy $vacancy)
    {
        $vacancy->update([
            'title'            => $request->title,
            'description'      => $request->description,
            'location'         => $request->location,
            'employment_type'  => $request->employment_type,
            'expiration_date'  => $request->expiration_date,
        ]);

        if ($request->action === 'published') {
            $vacancy->publish();
        }

        return redirect()
            ->route('vacancies.index')
            ->with('success', 'Vacancy updated successfully.');
    }

    public function destroy(Vacancy $vacancy)
    {
        $this->authorize('delete', $vacancy);

        $vacancy->delete();

        return redirect()
            ->route('vacancies.index')
            ->with('success', 'Vacancy deleted successfully.');
    }
}
