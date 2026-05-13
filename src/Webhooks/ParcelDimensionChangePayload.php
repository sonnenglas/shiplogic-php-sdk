<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Webhooks;

class ParcelDimensionChangePayload
{
    /**
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $shipmentId,
        public readonly string $shipmentTrackingReference,
        public readonly string $parcelReference,
        public readonly string $source,
        public readonly string $eventType,
        public readonly string $eventTime,
        public readonly float $lengthCm,
        public readonly float $widthCm,
        public readonly float $heightCm,
        public readonly float $weightKg,
        public readonly array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            shipmentId: (int) ($payload['shipment_id'] ?? 0),
            shipmentTrackingReference: (string) ($payload['shipment_tracking_reference'] ?? ''),
            parcelReference: (string) ($payload['parcel_reference'] ?? ''),
            source: (string) ($payload['source'] ?? ''),
            eventType: (string) ($payload['event_type'] ?? ''),
            eventTime: (string) ($payload['event_time'] ?? ''),
            lengthCm: (float) ($payload['length_cm'] ?? 0),
            widthCm: (float) ($payload['width_cm'] ?? 0),
            heightCm: (float) ($payload['height_cm'] ?? 0),
            weightKg: (float) ($payload['weight_kg'] ?? 0),
            raw: $payload,
        );
    }
}
