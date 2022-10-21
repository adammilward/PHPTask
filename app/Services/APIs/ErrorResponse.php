<?php
/**
 * Created by PhpStorm.
 * User: adam
 * Date: 20/10/2022
 */

namespace App\Services\APIs;

/**
 * Provides a consistent format for exception information and errors.
 *
 * ! Warning this can reveal sensitive information to the client
 * ! Ensure sensitive information is only included while developing and debugging.
 *
 * @package App\Services\Api
 */
class ErrorResponse implements \JsonSerializable
{

    private string $exceptionMessage;

    private string $stackTrace;

    private string $type;

    /**
     * @param string $message
     */
    public function __construct(
        string $message,
        string $type = 'Server Exception',
        $stackTrace = ''
    )
    {
        $this->exceptionMessage = $message;
        $this->stackTrace = $stackTrace;
        $this->type = $type;
    }

    public function jsonSerialize(): mixed
    {
        return  [
            'error' => 'error',
            'type' => $this->type,
            'message' => $this->exceptionMessage,
            'stackTrace' => $this->stackTrace
        ];
    }
}
