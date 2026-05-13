<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Webhooks;

class ShipmentNotePayload
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $id,
        public readonly int $shipmentId,
        public readonly string $shipmentCustomTrackingReference,
        public readonly string $shipmentShortTrackingReference,
        public readonly string $message,
        public readonly string $type,
        public readonly string $timeCreated,
        public readonly string $timeModified,
        public readonly array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            id: (int) ($payload['id'] ?? 0),
            shipmentId: (int) ($payload['shipment_id'] ?? 0),
            shipmentCustomTrackingReference: (string) ($payload['shipment_custom_tracking_reference'] ?? ''),
            shipmentShortTrackingReference: (string) ($payload['shipment_short_tracking_reference'] ?? ''),
            message: (string) ($payload['message'] ?? ''),
            type: (string) ($payload['type'] ?? ''),
            timeCreated: (string) ($payload['time_created'] ?? ''),
            timeModified: (string) ($payload['time_modified'] ?? ''),
            raw: $payload,
        );
    }
}
