<?php

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

Route::get('/weather/forecast/cityId/{id}',  [WeatherController::class, 'getForecastByCityId']);

Route::get('/weather/city/id/{id}',  [WeatherController::class, 'getCityById']);

Route::get('/weather/allCities', [WeatherController::class, 'getAllCities']);

Route::get('/weather/city/cityName/{cityName}',  [WeatherController::class, 'getCityByName']);

Route::get('/weather/cities/match/{nameString}',  [WeatherController::class, 'matchCityNames']);


// todo delete if not working
//Route::resource('weather', WeatherController::class)
//    ->missing(function (Request $request) {
//        return Redirect::route('/');
//    });
