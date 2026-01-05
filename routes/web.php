<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VacancyController;
use App\Models\Company;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');


    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');


    Route::middleware('can:viewAny,' . Company::class)->group(function () {
        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('companies.index');
    });

    Route::middleware('can:create,' . Company::class)->group(function () {
        Route::get('/companies/create', [CompanyController::class, 'create'])
            ->name('companies.create');

        Route::post('/companies', [CompanyController::class, 'store'])
            ->name('companies.store');
    });

    Route::middleware('can:view,' . Company::class)->group(function () {
        Route::get('/companies/{company:slug}', [CompanyController::class, 'view'])
            ->name('companies.view');
    });

    Route::middleware('can:update,' . Company::class)->group(function () {
        Route::get('/companies/{company:slug}/edit', [CompanyController::class, 'edit'])
            ->name('companies.edit');

        Route::put('/companies/{company:slug}', [CompanyController::class, 'update'])
            ->name('companies.update');
    });

    Route::delete('/companies/{company:slug}', [CompanyController::class, 'destroy'])
        ->middleware('can:delete,' . Company::class)
        ->name('companies.destroy');

    Route::get('/vacancies', [VacancyController::class, 'index'])
        ->middleware('can:viewAny,' . Vacancy::class)
        ->name('vacancies.index');

    Route::get('/companies/{company:slug}/vacancies', [VacancyController::class, 'companyIndex'])
        ->middleware('can:viewCompanyVacancies,' . Vacancy::class)
        ->name('vacancies.company');

    Route::middleware('can:create,' . Vacancy::class)->group(function () {
        Route::get('/vacancies/create', [VacancyController::class, 'create'])
            ->name('vacancies.create');

        Route::post('/vacancies', [VacancyController::class, 'store'])
            ->name('vacancies.store');
    });

    Route::middleware('can:update,' . Vacancy::class)->group(function () {
        Route::get('/vacancies/{vacancy}/edit', [VacancyController::class, 'edit'])
            ->name('vacancies.edit');

        Route::put('/vacancies/{vacancy}', [VacancyController::class, 'update'])
            ->name('vacancies.update');
    });

    Route::get('/vacancies/{vacancy}', [VacancyController::class, 'view'])
        ->middleware('can:view,' . Vacancy::class)
        ->name('vacancies.view');

    Route::delete('/vacancies/{vacancy}', [VacancyController::class, 'destroy'])
        ->middleware('can:delete,vacancy')
        ->name('vacancies.destroy');
});
