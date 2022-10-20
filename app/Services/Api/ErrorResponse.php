<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\Api;

class ErrorResponse
{

    private string $errorMessage;
    private string $stackTrace;

    /**
     * @param string $message
     */
    public function __construct(string $message, $stackTrace = '')
    {
        $this->errorMessage = $message;
        $this->stackTrace = $stackTrace;
    }

    public function jsonSerialize(): mixed
    {
        return  [
            'error' => 'error',
            'message' => $this->errorMessage,
            'stackTrace' => $this->stackTrace
        ];
    }
}
