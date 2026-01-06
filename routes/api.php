<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicVacancyController;
use App\Http\Controllers\Api\CandidateApplicationController;

Route::prefix('v1')->group(function () {

    Route::prefix('public')->group(function () {

        Route::get('/vacancies', [PublicVacancyController::class, 'index']);
        Route::get('/vacancies/{vacancy}', [PublicVacancyController::class, 'show']);

        Route::post(
            '/vacancies/{vacancy}/apply',
            [CandidateApplicationController::class, 'store']
        );
    });
});
