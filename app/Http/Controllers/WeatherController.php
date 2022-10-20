<?php

namespace App\Http\Controllers;

use App\Models\WeatherModelException;
use App\Services\Api\ErrorResponse;
use App\Services\Api\SuccessResponse;
use App\Services\Weather\OpenWeather;
use App\Services\Weather\WeatherServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class WeatherController extends Controller
{
    private ResponseFactory $jsonResponseFactory;

    //todo put back
    //private WeatherServiceInterface $weather;
    private OpenWeather $weather;

    // debug flag set to true to return sensitive debug info with on error.
    private bool $debug = false;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(
        WeatherServiceInterface $weather,
        ResponseFactory $jsonResponseFactory,
    )
    {
        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->weather = $weather;

        // set this flag based on config setting (ie false for live server)
        // or user privileges etc.
        $this->debug = true;
    }

    /**
     */
    public function getCities()
    {
        try {
            return $this->successResponse($this->weather->getCities());
        } catch (WeatherModelException $e) {
            return $this->ExcptionResponse($e);
        }
    }

    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function get($id)
    {

        $weatherData = $this->weather->get();

        return $this->successResponse($weatherData);
    }

    private function successResponse(array|\JsonSerializable $payload)
    {
        return  $this
            ->jsonResponseFactory
            ->json(new SuccessResponse($payload));
    }

    private function excptionResponse(\Exception $e)
    {
        if ($this->debug) {
            return $this
                ->jsonResponseFactory
                ->json(
                    new ErrorResponse(
                        $e->getMessage(),
                        $e->getTraceAsString()
                    )
                );
        } else {
            return $this
                ->jsonResponseFactory
                ->json(
                    new ErrorResponse('Server Error')
                );
        }
    }
}
