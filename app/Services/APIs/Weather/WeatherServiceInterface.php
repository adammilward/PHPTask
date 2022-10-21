<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\APIs\Weather;


use App\Models\ForecastCity;

Interface WeatherServiceInterface
{
    public function getForecastByCityId(int $id): WeatherForecastData;

    public function getCityById(int $id): ForecastCity;

    public function getAllCities(): array;

    public function matchCityNames(string $nameString): array;

    public function getCityNearest(float $lat, float $lon): ForecastCity;
}
