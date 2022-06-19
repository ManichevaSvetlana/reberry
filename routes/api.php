<?php

use Illuminate\Http\Request;
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

/* Auth Routes */
// Login & Issue a new token
Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
// Register & Issue a new token
Route::post('/register', [\App\Http\Controllers\API\AuthController::class, 'register']);
// Sanctum protected routes
Route::group(['middleware' => ['auth:sanctum', 'refresh']], function () {
    // Get countries with the statistics information
    Route::get('/countries', [\App\Http\Controllers\API\CountriesController::class, 'resources']);
    // Get information about the country
    Route::get('/countries/{code}', [\App\Http\Controllers\API\CountriesController::class, 'resource']);
    // Get total information about today
    Route::get('/total', [\App\Http\Controllers\API\CountriesController::class, 'total']);
    // Get the current user
    Route::get('/user', [\App\Http\Controllers\API\AuthController::class, 'user']);
    // Forget token
    Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
});
/* End of Auth Routes */
