<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

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
}
