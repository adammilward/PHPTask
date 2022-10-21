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

    public function getForecastByCityId(int $cityId): WeatherForecastData
    {
        $apiKey = $this->model->getApiKey();
        $googleApiUrl =
            "https://api.openweathermap.org/data/2.5/weather?id="
            . $cityId
            . "&lang=en&units=metric&APPID="
            . $apiKey;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);
        $data = json_decode($response, true);

        return $this->buildForecastFromData($data);
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

    public function getCityNearest(float $lat, float $lon): ForecastCity
    {
        return $this->model->getCityNearest($lat, $lon);
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

    private function buildForecastFromData(array $data): WeatherForecastData
    {
        return new WeatherForecastData(
            $this->getCityById($data['id']),
            $data['weather'][0]['main'],
            $data['weather'][0]['description'],
            $data['main']['temp'],
            $data['main']['temp_min'],
            $data['main']['temp_max'],
            $data['main']['pressure'],
            $data['main']['humidity'],
            $data['wind']['speed'],
            $data['wind']['deg'],
            $data['wind']['gust'],
        );
    }
}
