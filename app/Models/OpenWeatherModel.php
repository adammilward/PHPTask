<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;

/**
 * Data storage for OpenWeatherService
 *
 * Ideally this would use a database rather than flat files,
 * however my computer's mysql server is not working,
 * I hope this is sufficient for proof of concept.
 *
 * Open weather API reference can be found here
 * https://openweathermap.org/current#one
 *
 * @package App\Models
 */
class OpenWeatherModel extends Model
{
    use HasFactory;

    /**
     * https://home.openweathermap.org/api_keys
     */
    const CONFIG_API_KEY = 'openWeather.apiKey';

    /**
     * file can be downloaded from:
     * https://openweathermap.org/current#bulk
     */
    const CONFIG_FILE_PATH = 'openWeather.citiesFilePath';

    private FilesystemManager $fileManager;

    public function __construct(
        FilesystemManager $fileManager,
        array $attributes = []
    ) {
        $this->fileManager = $fileManager;
        parent::__construct($attributes);
    }

    /**
     * returns the api key
     * @return string
     * @throws WeatherModelException
     */
    public function getApiKey(): string {
        $apiKey = config(self::CONFIG_API_KEY);
        if (! is_string($apiKey)) {
            throw new WeatherModelException(
                'Open weather config key "'
                . self::CONFIG_API_KEY
                . '" returned and invalid value'
            );
        }
        return $apiKey;
    }

    /**
     * returns a
     *
     * @return array
     * @throws WeatherModelException
     */
    public function getAllCities(): array
    {
        $citiesPath = config(self::CONFIG_FILE_PATH);
        if (! is_string($citiesPath)) {
            throw new WeatherModelException(
                'Open weather config key "'
                . self::CONFIG_FILE_PATH
                . '" returned and invalid value'
            );
        }

        $citiesFileContents = $this->fileManager->get($citiesPath, 'Contents');

        if (! is_string($citiesFileContents) || ! $citiesFileContents) {
            throw new WeatherModelException("File $citiesPath returned an invalid string");
        }
        $citiesJson = json_decode($citiesFileContents, true);

        return $citiesJson;
    }

    /**
     * @param string $nameString
     * @return array
     * @throws WeatherModelException
     */
    public function getCitiesMatching(string $nameString): array
    {
        $nameString = strtolower($nameString);
        $exact = [];
        $startsWith = [];
        $contains = [];
        foreach ($this->getAllCities() as $city) {
            if (strtolower($city['name']) === $nameString) {
                $exact = [$this->buildCityFromArray($city)];
            } else {
                $stripos = stripos($city['name'], $nameString);
                if  ($stripos === 0) {
                    array_push($startsWith, $this->buildCityFromArray($city));
                } elseif ($stripos > 1) {
                    array_push($contains, $this->buildCityFromArray($city));
                }
            }
        }
        return array_merge($exact, $startsWith, $contains);
    }

    /**
     * @param int $id
     * @return ForecastCity
     * @throws CityNotFoundException
     * @throws WeatherModelException
     */
    public function getCityById(int $id): ForecastCity
    {
        foreach ($this->getAllCities() as $city) {
            if ($city['id'] === $id) {
                return $this->buildCityFromArray($city);
            }
        }
        throw new CityNotFoundException();
    }

    public function getCityNearest(float $lat, float $lon)
    {
        return $this->buildCityFromArray(
            $this->findCityNearest(
                $this->getAllCities(),
                $lat,
                $lon
            )
        );
    }

    private function findCityNearest(
        array $cities,
        float $lat,
        float $lon
    ): array
    {
        $nearestCity = reset($cities);
        $nearestCityDistance = $this->calcDistance(
            $nearestCity['coord']['lat'],
            $nearestCity['coord']['lon'],
            $lat,
            $lon
        );
        foreach ($cities as $city) {
            $distance = $this->calcDistance(
                $city['coord']['lat'],
                $city['coord']['lon'],
                $lat,
                $lon
            );
            if ($distance < $nearestCityDistance) {
                $nearestCityDistance = $distance;
                $nearestCity = $city;
            }
        }
        return $nearestCity;
    }

    /***
     * @param array $city
     * @return ForecastCity
     */
    private function buildCityFromArray(array $city): ForecastCity
    {
        return new ForecastCity(
            $city['id'],
            $city['name'],
            $city['country'],
            $city['coord']['lat'],
            $city['coord']['lon'],
        );
    }

    /**
     * use Haversine Formula to calculate nearest distance between two points
     *
     * @param float $cityLat
     * @param float $cityLon
     * @param float $lat
     * @param float $lon
     * @return float
     */
    private function calcDistance(
        float $cityLat,
        float $cityLon,
        float $lat,
        float $lon
    )
    {
        // convert to rad
        $cityLat = deg2rad($cityLat);
        $cityLon = deg2rad($cityLon);
        $lat = deg2rad($lat);
        $lon = deg2rad($lon);

        //Haversine Formula
        $dlong = $cityLon - $lon;
        $dlati = $cityLat - $lat;

        $val =
            pow(sin($dlati / 2),2)
                +
            cos($cityLat) * cos($lat) * pow(sin($dlong / 2), 2);

        $res = asin(sqrt($val));

        return ($res);
    }
}
