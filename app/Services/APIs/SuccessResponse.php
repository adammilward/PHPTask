<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\APIs;

/**
 * Sends API response data (payload) in a consistent format
 *
 * @package App\Services\Api
 */
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

    /**
     * @return mixed
     */
    public function jsonSerialize(): array
    {
        return  [
            'success' => 'success',
            'payload' => $this->payload
        ];
    }
}
