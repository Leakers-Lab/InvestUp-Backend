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
Route::get('/categories', [\App\Http\Controllers\Company\Category\CategoriesController::class, 'index']);

// Router for get project
Route::get('/project/{alias}', [\App\Http\Controllers\Company\Project\ProjectController::class, 'index']);

// Router for auth
Route::post('/register', [\App\Http\Controllers\User\Auth\RegistrationController::class, 'index']);
Route::post('/login', [\App\Http\Controllers\User\Auth\LoginController::class, 'index']);

Route::group(['middleware' =>'auth:sanctum'], function () {

    // Router for logout user
    Route::post('/logout', [\App\Http\Controllers\User\Auth\LogOutController::class, 'index']);
});
