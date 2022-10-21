<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\APIs\Weather;


Interface WeatherServiceInterface
{
    public function getForecastByCityId(int $id);

    public function getCityById(int $id);

    public function getAllCities();

    public function getCityByName(string $cityName);

    public function matchCityNames(string $nameString);
}
