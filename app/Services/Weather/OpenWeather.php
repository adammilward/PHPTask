<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\Weather;

use App\Models\OpenWeatherModel;

class OpenWeather implements WeatherServiceInterface
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
    public function getCities(): array
    {
        return $this->model->getCities();
    }

    public function get()
    {
        $data = $this->model->getWeatherForId(123);

        return ['weather' => $data];
    }
}
