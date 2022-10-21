<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\APIs\Weather;

use App\Models\ForecastCity;
use App\Models\OpenWeatherModel;

class OpenWeatherService implements WeatherServiceInterface
{
    private OpenWeatherModel $model;

    public function __construct(OpenWeatherModel $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     * @throws \App\Models\WeatherModelException
     */
    public function getAllCities(): array
    {
        return $this->model->getAllCities();
    }


    public function getForecastByCityId(int $id): WeatherForecastData
    {
        $data = $this->model->getWeatherForId(123);
        return $data;
    }

    /**
     * @param int $id
     * @return ForecastCity
     * @throws \App\Models\CityNotFoundException
     * @throws \App\Models\WeatherModelException
     */
    public function getCityById(int $id): ForecastCity
    {
        return $this->model->getCityById($id);
    }

    /**
     * @param string $nameString
     * @return array
     * @throws \App\Models\WeatherModelException
     */
    public function matchCityNames(string $nameString): array
    {
        return $this->model->getCitiesMatching($nameString);
    }
}
