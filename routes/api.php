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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/weather/{id}', [WeatherController::class, 'get']);


//Route::get('/weather/{id}', function ($id) {
//    $weatherData = \App\Providers\WeatherProvider::get();
//    return [
//        'succes' => 'success',
//        'route' => '/weather/' . $id,
//        'payload' => [$weatherData]
//    ];
//});

// todo delete if not working
//Route::resource('weather', WeatherController::class)
//    ->missing(function (Request $request) {
//        return Redirect::route('/');
//    });
