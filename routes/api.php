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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

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
Route::get('/project/{alias}/comments', [\App\Http\Controllers\Company\Project\ProjectController::class, 'index']);

// Router for auth
Route::post('/register', [\App\Http\Controllers\User\Auth\RegistrationController::class, 'index']);
Route::post('/login', [\App\Http\Controllers\User\Auth\LoginController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Router for Plans
    Route::group(['middleware' => 'status'], function () {
        // Router for Project
        Route::post('/new/project', [\App\Http\Controllers\Company\Project\ProjectController::class, 'create']);

        // Router for Project Plans
        Route::post('/projects/plans/add', [\App\Http\Controllers\Company\Project\ProjectsController::class, 'create']);
        Route::post('/projects/comments/add', [\App\Http\Controllers\User\Comments\CommentsController::class, 'create']);

        // Router for Company
        Route::post('/register/company', [\App\Http\Controllers\Company\CompanyController::class, 'create']);
    });

    // Router for logout user
    Route::post('/logout', [\App\Http\Controllers\User\Auth\LogOutController::class, 'index']);
});
