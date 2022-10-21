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
 * @package App\Models
 */
class OpenWeatherModel extends Model
{
    use HasFactory;

    const CONFIG_API_KEY = 'openWeather.apiKey';

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
    private function getApiKey(): string {
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

    public function getWeatherForId(int $cityId)
    {

        $apiKey = $this->getApiKey();
        $googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        curl_close($ch);
        $data = json_decode($response);
        $currentTime = time();

        return $data;

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
}
