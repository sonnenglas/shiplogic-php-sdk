<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Responses;

class ShipmentResponse
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $id,
        public readonly string $shortTrackingReference,
        public readonly string $customTrackingReference,
        public readonly string $status,
        public readonly string $serviceLevelCode,
        public readonly ?string $serviceLevelName,
        public readonly float $rate,
        public readonly ?string $timeCreated,
        public readonly array $raw,
    ) {}
}
