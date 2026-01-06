<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Public Vacancies",
    description: "Public vacancies endpoints"
)]
class PublicVacancyController extends Controller
{
    #[OA\Get(
        path: "/api/v1/public/vacancies",
        summary: "List public vacancies",
        tags: ["Public Vacancies"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Vacancies list"
            )
        ]
    )]
    public function index(Request $request)
    {
        $vacancies = Vacancy::query()
            ->published()
            ->where('is_active', true)
            ->with('company:id,name,slug')
            ->select([
                'id',
                'company_id',
                'title',
                'employment_type',
                'location',
                'published_at',
            ])
            ->latest('published_at')
            ->paginate(10);

        return response()->json($vacancies);
    }

    #[OA\Get(
        path: "/api/v1/public/vacancies/{vacancy}",
        summary: "Get vacancy details",
        tags: ["Public Vacancies"],
        parameters: [
            new OA\Parameter(
                name: "vacancy",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Vacancy details"),
            new OA\Response(response: 404, description: "Not found")
        ]
    )]
    public function show(Vacancy $vacancy)
    {
        abort_unless($vacancy->isPublished(), 404);

        return response()->json(
            $vacancy->load('company:id,name,slug')
        );
    }
}
