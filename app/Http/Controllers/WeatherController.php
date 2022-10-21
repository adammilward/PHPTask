<?php

namespace App\Http\Controllers;

use App\Models\WeatherModelException;
use App\Services\APIs\ExceptionResponse;
use App\Services\APIs\SuccessResponse;
use App\Services\APIs\Weather\OpenWeatherService;
use App\Services\APIs\Weather\WeatherServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class WeatherController extends Controller
{
    private ResponseFactory $jsonResponseFactory;

    //todo put back
    //private WeatherServiceInterface $weather;
    private OpenWeatherService $weather;

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
    public function getAllCities()
    {
        try {
            return $this->successResponse($this->weather->getAllCities());
        } catch (WeatherModelException $e) {
            return $this->ExcptionResponse($e);
        }
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
                    new ExceptionResponse(
                        $e->getMessage(),
                        get_class($e),
                        $e->getTraceAsString()
                    )
                );
        } else {
            return $this
                ->jsonResponseFactory
                ->json(
                    new ExceptionResponse('The server encountered and error processing your request')
                );
        }
    }

    public function getForecastByCityId(int $id)
    {
        $weatherData = $this->weather->getForecastByCityId($id);
        return $this->successResponse($weatherData);
    }

    public function getCityById(int $id)
    {
        // TODO: Implement getCityById() method.
    }

    public function getCityByName(string $cityName)
    {
        // TODO: Implement getCityByName() method.
    }

    public function matchCityNames(string $nameString): JsonResponse
    {
        return  $this
            ->jsonResponseFactory
            ->json(new SuccessResponse($this->weather->matchCityNames($nameString)));
    }
}
