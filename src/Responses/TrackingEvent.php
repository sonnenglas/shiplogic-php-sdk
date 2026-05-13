<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Responses;

class TrackingEvent
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $id,
        public readonly string $date,
        public readonly string $status,
        public readonly string $message,
        public readonly ?string $location,
        public readonly ?string $source,
        public readonly int $parcelId,
        public readonly array $raw,
    ) {}
}
