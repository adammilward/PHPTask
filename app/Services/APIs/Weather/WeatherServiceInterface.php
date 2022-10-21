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

    /**
     * @param int $id
     * @return ForecastCity
     * @throws \App\Models\CityNotFoundException
     * @throws \App\Models\WeatherModelException
     */
    public function getCityById(int $id): ForecastCity;

    /**
     * @return array
     * @throws \App\Models\WeatherModelException
     */
    public function getAllCities(): array;

    /**
     * @param string $nameString
     * @return array
     * @throws \App\Models\WeatherModelException
     */
    public function matchCityNames(string $nameString): array;

    /**
     * @param float $lat
     * @param float $lon
     * @return ForecastCity
     * @throws \App\Models\WeatherModelException
     */
    public function getCityNearest(float $lat, float $lon): ForecastCity;
}
