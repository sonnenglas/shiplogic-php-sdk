<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Responses;

class LabelResponse
{
    public function __construct(
        public readonly string $url,
    ) {}
}
