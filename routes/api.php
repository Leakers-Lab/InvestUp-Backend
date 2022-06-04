<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Router for get all active categories
Route::get('/companies', [\App\Http\Controllers\Company\CompaniesController::class, 'index']);
Route::get('/company/{alias}', [\App\Http\Controllers\Company\CompanyController::class, 'index']);

// Router for get all active categories
Route::get('/categories', [\App\Http\Controllers\Company\Category\CategoriesController::class, 'index']);
Route::get('/category/{alias}', [\App\Http\Controllers\Company\Category\CategoryController::class, 'index']);

// Router for get project
Route::get('/projects', [\App\Http\Controllers\Company\Project\ProjectsController::class, 'index']);
Route::get('/project/{alias}', [\App\Http\Controllers\Company\Project\ProjectController::class, 'index']);
Route::get('/project/{alias}/plans', [\App\Http\Controllers\Company\Project\ProjectController::class, 'index']);
Route::get('/project/{alias}/donations', [\App\Http\Controllers\Company\Project\DonationsController::class, 'index']);

// Router for auth
Route::post('/register', [\App\Http\Controllers\User\Auth\RegistrationController::class, 'index']);
Route::post('/login', [\App\Http\Controllers\User\Auth\LoginController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Router for Plans
    Route::group(['middleware' => 'status'], function () {
        // Router for Project
        Route::get('/user', [\App\Http\Controllers\User\ProfileController::class, 'index']);
        Route::post('/user/update', [\App\Http\Controllers\User\ProfileController::class, 'update']);

        // Router for Project
        Route::post('/project/add', [\App\Http\Controllers\Company\Project\ProjectController::class, 'create']);
        Route::post('/project/{alias}/update', [\App\Http\Controllers\Company\Project\ProjectController::class, 'update']);

        // Router for Project Plans
        Route::post('/project/{alias}/plans/add', [\App\Http\Controllers\Company\Project\PlansController::class, 'create']);
        Route::post('/project/{alias}/comments/add', [\App\Http\Controllers\User\Comments\CommentsController::class, 'create']);
        Route::post('/project/{alias}/gallery/add', [\App\Http\Controllers\Company\Project\GalleryController::class, 'create']);
        Route::post('/project/{alias}/donations/add', [\App\Http\Controllers\Company\Project\DonationsController::class, 'create']);

        // Router for Company
        Route::post('/company/register', [\App\Http\Controllers\Company\CompanyController::class, 'create']);
        Route::post('/company/{alias}/update', [\App\Http\Controllers\Company\CompanyController::class, 'update']);
    });

    // Router for logout user
    Route::post('/logout', [\App\Http\Controllers\User\Auth\LogOutController::class, 'index']);
});
