<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Responses;

class RateQuote
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly string $serviceLevelCode,
        public readonly string $serviceLevelName,
        public readonly int $serviceLevelId,
        public readonly float $rate,
        public readonly ?string $estimatedCollection,
        public readonly ?string $estimatedDeliveryFrom,
        public readonly ?string $estimatedDeliveryTo,
        public readonly array $raw,
    ) {}
}
