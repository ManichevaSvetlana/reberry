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
    // Get the current user
    Route::get('/user', [\App\Http\Controllers\API\AuthController::class, 'user']);
    // Forget token
    Route::post('/logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
});
/* End of Auth Routes */
