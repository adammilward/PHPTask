<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\Api;

class SuccessResponse implements \JsonSerializable
{
    private array|\JsonSerializable $payload;

    /**
   * @param \JsonSerializable $citiesData
   */
  public function __construct(array|\JsonSerializable $citiesData)
  {
      $this->payload = $citiesData;
  }

    public function jsonSerialize(): mixed
    {
        return  [
            'success' => 'success',
            'payload' => $this->payload
        ];
    }
}
