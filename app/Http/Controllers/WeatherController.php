<?php

namespace App\Http\Controllers;

use App\Models\CityNotFoundException;
use App\Models\ForecastCity;
use App\Services\APIs\ErrorResponse;
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


    public function getAllCities()
    {
        try {
            return $this->successResponse($this->weather->getAllCities());
        } catch (\Exception $e) {
            return $this->ExcptionResponse($e);
        }
    }

    public function getForecastByCityId(int $id): JsonResponse
    {
        $weatherData = $this->weather->getForecastByCityId($id);
        return $this->successResponse($weatherData);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getCityById(int $id): JsonResponse
    {
        try {
            return $this->successResponse($this->weather->getCityById($id));
        } catch (CityNotFoundException $e) {
            return $this
                ->jsonResponseFactory
                ->json(new ErrorResponse(
                    'No city was found matching id ' . $id,
                    'CityNotFound'
                )
            );
        } catch (\Exception $e) {
            return $this->ExcptionResponse($e);
        }
    }

    public function getCityNearest(float $lat, float $lon): ForecastCity
    {
        return $this->weather->getCityNearest($lat, $lon);
    }

    /**
     * @param string $nameString
     * @return JsonResponse
     */
    public function matchCityNames(string $nameString): JsonResponse
    {
        $nameString  = trim($nameString);
        try {
            return $this->successResponse($this->weather->matchCityNames($nameString));
        } catch (\Exception $e) {
            return $this->ExcptionResponse($e);
        }
    }

    private function successResponse(array|\JsonSerializable $payload): JsonResponse
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
                        get_class($e),
                        $e->getTraceAsString()
                    )
                );
        } else {
            return $this
                ->jsonResponseFactory
                ->json(
                    new ErrorResponse('The server encountered and error processing your request')
                );
        }
    }
}
