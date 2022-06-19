<?php

use App\Models\Country;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/testss', function () {
    foreach (Country::all() as $country) {
        $country->save();
    }
});

Route::get('/', function () {
    return view('welcome');
});
