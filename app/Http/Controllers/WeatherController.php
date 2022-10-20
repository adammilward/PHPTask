<?php

namespace App\Http\Controllers;

use App\Providers\AdamTestServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\WeatherServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class WeatherController extends Controller
{
    //private WeatherProvider $weatherProvider;
    private AdamTestServiceProvider $test;
    private ResponseFactory $jsonResponseFactory;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(
        WeatherServiceProvider $weatherProvider,
        //AdamTestServiceProvider $test,
        //AppServiceProvider $test,
        ResponseFactory $jsonResponseFactory,
    )
    {
        //$this->weatherProvider = $weatherProvider;
        $this->jsonResponseFactory = $jsonResponseFactory;
        //$this->test = $test;
    }


    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function get($id)
    {
        $weatherData = \App\Providers\WeatherServiceProvider::get();

        return $this->jsonResponseFactory->json(
            [
                'succes' => 'success',
                'route' => '/weather/' . $id,
                'payload' => $weatherData
            ]
        );
    }
}
