<?php

namespace App\Http\Controllers;

use App\Models\CityNotFoundException;
use App\Models\ForecastCity;
use App\Services\APIs\ErrorResponse;
use App\Services\APIs\SuccessResponse;
use App\Services\APIs\Weather\WeatherServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;

class WeatherController extends Controller
{
    private ResponseFactory $jsonResponseFactory;

    private WeatherServiceInterface $weather;

    // debug flag set to true to exception trace and class name
    // ! caution should be false on live server.
    private bool $debug = false;

    public function __construct(
        WeatherServiceInterface $weather,
        ResponseFactory $jsonResponseFactory,
    )
    {
        $this->middleware('auth:api');

        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->weather = $weather;
    }

    /**
     * Api route /weather/allCities
     * returns all cities currently in storage
     *
     * @return JsonResponse
     */
    public function getAllCities()
    {
        try {
            return $this->successResponse($this->weather->getAllCities());
        } catch (\Exception $e) {
            return $this->ExcptionResponse($e);
        }
    }

    /**
     * Api route /weather/forecast/cityId/{id}
     * Returns a forecast for city id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getForecastByCityId(int $id): JsonResponse
    {
        $weatherData = $this->weather->getForecastByCityId($id);
        return $this->successResponse($weatherData);
    }

    /**
     * Api route /weather/city/id/{id}
     * returns city data by id.
     *
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

    /**
     * Api route /weather/city/lat/{lat}/lon/{lon}
     * returns the nearest city to the provided coordinates
     * (in decimal degrees)
     *
     * @param float $lat
     * @param float $lon
     * @return ForecastCity
     */
    public function getCityNearest(float $lat, float $lon): ForecastCity
    {
        return $this->weather->getCityNearest($lat, $lon);
    }

    /**
     * Api route /weather/cities/match/{nameString}
     * returns cities matching the string provided
     * Exact match first, followed by cities starting with $nameString
     * followed by city names containing $nameString
     *
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

    /**
     * Use for returning a consistent success response containing the payload.
     * Payload data should be in objects to ensure correct value types.
     * This could restrict the payload to a set of permitted Serializable Objects, to
     * prevent uncontrolled bundles of data in jagged arrays being sent to the client.
     *
     * @param array|\JsonSerializable $payload - should be a typed array or JsonSerializable object
     * @return JsonResponse
     */
    private function successResponse(array|\JsonSerializable $payload): JsonResponse
    {
        return  $this
            ->jsonResponseFactory
            ->json(new SuccessResponse($payload));
    }

    /**
     * Returns a consistent error response to the client.
     *
     * @param \Exception $e
     * @return JsonResponse
     */
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
