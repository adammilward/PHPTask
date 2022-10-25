<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

/*
|--------------------------------------------------------------------------
| Weather API Routes
|--------------------------------------------------------------------------
*/

Route::controller(WeatherController::class)->group(function () {
    Route::get('/weather/forecast/cityId/{id}', 'getForecastByCityId');
    Route::get('/weather/city/id/{id}', 'getCityById');
    Route::get('/weather/allCities', 'getAllCities');
    Route::get('/weather/cities/match/{nameString}', 'matchCityNames');
    Route::get('/weather/city/lat/{lat}/lon/{lon}', 'getCityNearest');
});
