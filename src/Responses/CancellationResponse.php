<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Responses;

class CancellationResponse
{
    public function __construct(
        public readonly bool $success,
        public readonly string $trackingReference,
        public readonly ?string $error = null,
    ) {}
}
