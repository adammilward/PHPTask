<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 21/10/2022
 */

namespace App\Services\APIs\Weather;

use App\Models\ForecastCity;

class WeatherForecastData implements \JsonSerializable
{

    private ForecastCity $city;
    private string $outlook;
    private string $description;
    private float $temp;
    private float $temp_min;
    private float $temp_max;
    private float $pressure;
    private float $humidity;
    private float $windSpeed;
    private float $windAngle;
    private float $windGusts;

    /**
     * @param ForecastCity $city
     * @param string $outlook
     * @param string $description
     * @param float $temp
     * @param float $temp_min
     * @param float $temp_max
     * @param float $pressure
     * @param float $humidity
     * @param float $windSpeed
     * @param float $windAngle
     * @param float $windGusts
     */
    public function __construct(
        ForecastCity $city,
        string $outlook,
        string $description,
        float $temp,
        float $temp_min,
        float $temp_max,
        float $pressure,
        float $humidity,
        float $windSpeed,
        float $windAngle,
        float $windGusts,
    )
    {
        $this->city = $city;
        $this->outlook = $outlook;
        $this->description = $description;
        $this->temp = $temp;
        $this->temp_min = $temp_min;
        $this->temp_max = $temp_max;
        $this->pressure = $pressure;
        $this->humidity = $humidity;
        $this->windSpeed = $windSpeed;
        $this->windAngle = $windAngle;
        $this->windGusts = $windGusts;
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
