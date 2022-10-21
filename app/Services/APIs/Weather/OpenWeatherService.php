<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\APIs\Weather;

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
        return $this->model->getCities();
    }

    public function getForecastByCityId(int $id)
    {
        $data = $this->model->getWeatherForId(123);
        return $data;
    }

    public function getCityById(int $id)
    {
        // TODO: Implement getCityById() method.
    }


    public function getCityByName(string $cityName)
    {
        // TODO: Implement getCityByName() method.
    }

    public function matchCityNames(string $nameString): array
    {
        return $this->model->getCitiesMatching($nameString);
    }
}
