<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\VacationController;
use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Vacation;
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

    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

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

    Route::middleware('can:view,company')->group(function () {
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

    Route::get(
        '/companies/{company:slug}/employees/create',
        [EmployeeController::class, 'create']
    )
        ->name('employees.create');

    Route::post(
        '/companies/{company:slug}/employees',
        [EmployeeController::class, 'store']
    )
        ->name('employees.store');

    Route::get(
        '/companies/{company:slug}/employees/{user}',
        [EmployeeController::class, 'show']
    )
        ->name('employees.show');

    Route::get(
        '/companies/{company:slug}/employees/{user}/edit',
        [EmployeeController::class, 'edit']
    )
        ->name('employees.edit');

    Route::put(
        '/companies/{company:slug}/employees/{user}',
        [EmployeeController::class, 'update']
    )
        ->name('employees.update');

    Route::delete(
        '/companies/{company:slug}/employees/{user}',
        [EmployeeController::class, 'destroy']
    )
        ->name('employees.destroy');


    Route::get(
        '/companies/{company:slug}/positions/create',
        [PositionController::class, 'create']
    )
        ->name('positions.create');

    Route::post(
        '/companies/{company:slug}/positions',
        [PositionController::class, 'store']
    )
        ->name('positions.store');

    Route::get(
        '/companies/{company:slug}/positions/{position}/edit',
        [PositionController::class, 'edit']
    )
        ->name('positions.edit');

    Route::put(
        '/companies/{company:slug}/positions/{position}',
        [PositionController::class, 'update']
    )
        ->name('positions.update');

    Route::delete(
        '/companies/{company:slug}/positions/{position}',
        [PositionController::class, 'destroy']
    )
        ->name('positions.destroy');

    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->name('attendance.checkin');

    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->name('attendance.checkout');

    Route::post(
        '/companies/{company:slug}/employees/{user}/absence',
        [AttendanceController::class, 'markAbsent']
    )->name('attendance.absence');

    Route::get('/vacations/create', [VacationController::class, 'create'])
        ->middleware('can:create,' . Vacation::class)
        ->name('vacations.create');

    Route::post('/vacations', [VacationController::class, 'store'])
        ->middleware('can:create,' . Vacation::class)
        ->name('vacations.store');

    Route::post('/vacations/{vacation}/approve', [VacationController::class, 'approve'])
        ->middleware('can:approve,vacation')
        ->name('vacations.approve');

    Route::post('/vacations/{vacation}/reject', [VacationController::class, 'reject'])
        ->middleware('can:reject,vacation')
        ->name('vacations.reject');
});
