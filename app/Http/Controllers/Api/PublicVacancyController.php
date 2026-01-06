<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class PublicVacancyController extends Controller
{
    /**
     * GET /public/vacancies
     */
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

    /**
     * GET /public/vacancies/{id}
     */
    public function show(Vacancy $vacancy)
    {
        abort_unless($vacancy->isPublished(), 404);

        return response()->json(
            $vacancy->load('company:id,name,slug')
        );
    }
}
