<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCandidateApplicationRequest;
use App\Models\CandidateApplication;
use App\Models\Vacancy;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Candidates",
    description: "Candidate applications"
)]
class CandidateApplicationController extends Controller
{
    #[OA\Post(
        path: "/api/v1/public/vacancies/{vacancy}/apply",
        summary: "Apply for vacancy",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["full_name", "email", "cv"],
                    properties: [
                        new OA\Property(property: "full_name", type: "string"),
                        new OA\Property(property: "email", type: "string", format: "email"),
                        new OA\Property(property: "phone", type: "string", nullable: true),
                        new OA\Property(property: "cv", type: "string", format: "binary"),
                        new OA\Property(property: "linkedin_url", type: "string", nullable: true),
                        new OA\Property(property: "github_url", type: "string", nullable: true),
                        new OA\Property(property: "portfolio_url", type: "string", nullable: true),
                    ]
                )
            )
        ),
        tags: ["Candidates"],
        parameters: [
            new OA\Parameter(
                name: "vacancy",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 201, description: "Application created"),
            new OA\Response(response: 422, description: "Validation error"),
        ]
    )]
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
