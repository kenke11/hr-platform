<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCandidateApplicationRequest;
use App\Models\CandidateApplication;
use App\Models\Vacancy;

class CandidateApplicationController extends Controller
{
    /**
     * POST /public/vacancies/{vacancy}/apply
     */
    public function store(
        StoreCandidateApplicationRequest $request,
        Vacancy $vacancy
    ) {
        abort_unless($vacancy->isPublished(), 404);

        $cvPath = $request->file('cv')->store('cvs', 'public');

        $application = CandidateApplication::create([
            'company_id' => $vacancy->company_id,
            'vacancy_id' => $vacancy->id,

            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,

            'linkedin_url' => $request->linkedin_url,
            'github_url' => $request->github_url,
            'portfolio_url' => $request->portfolio_url,

            'cv_path' => $cvPath,
            'cover_letter' => $request->cover_letter,
        ]);

        return response()->json([
            'message' => 'Application submitted successfully',
            'id' => $application->id,
        ], 201);
    }
}
