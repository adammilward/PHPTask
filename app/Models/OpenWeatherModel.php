<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;

class OpenWeatherModel extends Model
{
    use HasFactory;

    const CONFIG_API_KEY = 'openWeather.apiKey';

    const CONFIG_FILE_PATH = 'openWeather.citiesFilePat h';

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
    public function getCities(): array
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
        $citiesJson = json_decode($citiesFileContents);

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

    public function getCitiesMatching(string $nameString): array
    {
        return [$nameString];
    }
}
