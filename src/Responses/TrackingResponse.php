<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Responses;

class TrackingResponse
{
    /**
     * @param  list<TrackingEvent>  $trackingEvents
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly string $status,
        public readonly string $shortTrackingReference,
        public readonly string $customTrackingReference,
        public readonly array $trackingEvents,
        public readonly array $raw,
    ) {}
}
