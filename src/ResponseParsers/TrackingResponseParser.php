<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ResponseParsers;

use Sonnenglas\Shiplogic\Responses\TrackingEvent;
use Sonnenglas\Shiplogic\Responses\TrackingResponse;

class TrackingResponseParser
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function parse(array $payload): TrackingResponse
    {
        $events = [];

        $rawEvents = $payload['tracking_events'] ?? [];

        if (is_array($rawEvents)) {
            foreach ($rawEvents as $event) {
                if (! is_array($event)) {
                    continue;
                }

                $events[] = new TrackingEvent(
                    id: (int) ($event['id'] ?? 0),
                    date: (string) ($event['date'] ?? ''),
                    status: (string) ($event['status'] ?? ''),
                    message: (string) ($event['message'] ?? ''),
                    location: isset($event['location']) ? (string) $event['location'] : null,
                    source: isset($event['source']) ? (string) $event['source'] : null,
                    parcelId: (int) ($event['parcel_id'] ?? 0),
                    raw: $event,
                );
            }
        }

        return new TrackingResponse(
            status: (string) ($payload['status'] ?? ''),
            shortTrackingReference: (string) ($payload['short_tracking_reference'] ?? ''),
            customTrackingReference: (string) ($payload['custom_tracking_reference'] ?? ''),
            trackingEvents: $events,
            raw: $payload,
        );
    }
}
