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
        summary: "Apply for a vacancy",
        description: "Submit a candidate application with CV upload",
        tags: ["Candidates"],

        parameters: [
            new OA\Parameter(
                name: "vacancy",
                description: "Vacancy ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],

        requestBody: new OA\RequestBody(
            required: true,
            content: [
                "multipart/form-data" => new OA\MediaType(
                    mediaType: "multipart/form-data",
                    schema: new OA\Schema(
                        required: ["full_name", "email", "cv"],
                        properties: [
                            new OA\Property(
                                property: "full_name",
                                type: "string",
                                example: "John Doe"
                            ),
                            new OA\Property(
                                property: "email",
                                type: "string",
                                format: "email",
                                example: "john@example.com"
                            ),
                            new OA\Property(
                                property: "phone",
                                type: "string",
                                nullable: true,
                                example: "+1 555 123 4567"
                            ),
                            new OA\Property(
                                property: "cv",
                                type: "string",
                                format: "binary"
                            ),
                            new OA\Property(
                                property: "linkedin_url",
                                type: "string",
                                nullable: true,
                                example: "https://linkedin.com/in/johndoe"
                            ),
                            new OA\Property(
                                property: "github_url",
                                type: "string",
                                nullable: true,
                                example: "https://github.com/johndoe"
                            ),
                            new OA\Property(
                                property: "portfolio_url",
                                type: "string",
                                nullable: true,
                                example: "https://johndoe.dev"
                            ),
                            new OA\Property(
                                property: "cover_letter",
                                type: "string",
                                nullable: true,
                                example: "I am very interested in this position."
                            ),
                        ]
                    )
                )
            ]
        ),

        responses: [
            new OA\Response(
                response: 201,
                description: "Application created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "id", type: "integer"),
                    ],
                    example: [
                        "message" => "Application submitted successfully",
                        "id" => 10
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Vacancy not found or not published"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
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
