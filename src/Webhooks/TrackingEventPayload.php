<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Webhooks;

use Sonnenglas\Shiplogic\Responses\TrackingEvent;

class TrackingEventPayload
{
    /**
     * @param  list<TrackingEvent>  $trackingEvents
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $shipmentId,
        public readonly string $customTrackingReference,
        public readonly string $shortTrackingReference,
        public readonly string $status,
        public readonly string $eventTime,
        public readonly ?string $serviceLevelCode,
        public readonly array $trackingEvents,
        public readonly array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $events = [];

        if (isset($payload['tracking_events']) && is_array($payload['tracking_events'])) {
            foreach ($payload['tracking_events'] as $event) {
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

        return new self(
            shipmentId: (int) ($payload['shipment_id'] ?? 0),
            customTrackingReference: (string) ($payload['custom_tracking_reference'] ?? ''),
            shortTrackingReference: (string) ($payload['short_tracking_reference'] ?? ''),
            status: (string) ($payload['status'] ?? ''),
            eventTime: (string) ($payload['event_time'] ?? ''),
            serviceLevelCode: isset($payload['service_level_code']) ? (string) $payload['service_level_code'] : null,
            trackingEvents: $events,
            raw: $payload,
        );
    }
}
