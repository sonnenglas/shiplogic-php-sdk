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
        $shipment = $this->extractShipment($payload);

        $events = [];

        $rawEvents = $shipment['tracking_events'] ?? [];

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
            status: (string) ($shipment['status'] ?? ''),
            shortTrackingReference: (string) ($shipment['short_tracking_reference'] ?? ''),
            customTrackingReference: (string) ($shipment['custom_tracking_reference'] ?? ''),
            trackingEvents: $events,
            raw: $payload,
        );
    }

    /**
     * The `GET /tracking/shipments` endpoint wraps the shipment in a
     * `shipments` array (alongside `tracking_steps`). Fall back to the
     * payload itself for the flat shape used by tracking-event webhooks.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function extractShipment(array $payload): array
    {
        if (isset($payload['shipments']) && is_array($payload['shipments']) && count($payload['shipments']) > 0) {
            $first = reset($payload['shipments']);

            return is_array($first) ? $first : [];
        }

        return $payload;
    }
}
