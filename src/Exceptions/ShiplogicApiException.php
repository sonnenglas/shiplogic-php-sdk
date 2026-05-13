<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Exceptions;

use Exception;

class ShiplogicApiException extends Exception
{
    public function __construct(
        string $message = '',
        protected int $statusCode = 0,
        protected ?string $responseBody = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }
}
