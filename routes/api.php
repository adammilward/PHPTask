<?php

use App\Http\Controllers\Api\WeatherApiController;
use Illuminate\Support\Facades\Auth;
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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



/*
|--------------------------------------------------------------------------
| Weather API Routes
|--------------------------------------------------------------------------
*/
Route::get('/weather/forecast/cityId/{id}',  [WeatherApiController::class, 'getForecastByCityId']);
Route::get('/weather/city/id/{id}',  [WeatherApiController::class, 'getCityById']);
Route::get('/weather/allCities', [WeatherApiController::class, 'getAllCities']);
Route::get('/weather/cities/match/{nameString}',  [WeatherApiController::class, 'matchCityNames']);
Route::get('/weather/city/lat/{lat}/lon/{lon}',  [WeatherApiController::class, 'getCityNearest']);

// todo delete if not working
//Route::resource('weather', WeatherController::class)
//    ->missing(function (Request $request) {
//        return Redirect::route('/');
//    });
