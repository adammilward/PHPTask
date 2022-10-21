<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 21/10/2022
 */

namespace App\Models;

/**
 * provides a consistent data structure for implementations of WeatherServiceInterface
 *
 * @package App\Models
 */
class ForecastCity implements \JsonSerializable
{
    private int $id;
    private string $name;
    private string $country;
    private float $lat;
    private float $lon;

    /**
   * @param int $id
   * @param string $name
   * @param string $country
   * @param float $lat
   * @param float $lon
   */
  public function __construct(
      int $id,
      string $name,
      string $country,
      float $lat,
      float $lon
  )
  {
      $this->id = $id;
      $this->name = $name;
      $this->country = $country;
      $this->lat = $lat;
      $this->lon = $lon;
  }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "country" => $this->country,
            "lon" => $this->lat,
            "lat" => $this->lon,
        ];
    }
}
