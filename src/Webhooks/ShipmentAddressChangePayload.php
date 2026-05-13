<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Webhooks;

class ShipmentAddressChangePayload
{
    /**
     * @param  array<string, mixed>  $newDeliveryAddress
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $shipmentId,
        public readonly string $shipmentTrackingReference,
        public readonly array $newDeliveryAddress,
        public readonly array $raw,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromArray(array $payload): self
    {
        $newAddress = $payload['new_delivery_address'] ?? [];

        return new self(
            shipmentId: (int) ($payload['shipment_id'] ?? 0),
            shipmentTrackingReference: (string) ($payload['shipment_tracking_reference'] ?? ''),
            newDeliveryAddress: is_array($newAddress) ? $newAddress : [],
            raw: $payload,
        );
    }
}
