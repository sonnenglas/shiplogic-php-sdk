<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ValueObjects;

use Sonnenglas\Shiplogic\Enums\ServiceLevel;
use Sonnenglas\Shiplogic\Exceptions\InvalidArgumentException;

class ShipmentRequest
{
    /**
     * @param  list<Parcel>  $parcels
     */
    public function __construct(
        public readonly Address $collectionAddress,
        public readonly Contact $collectionContact,
        public readonly Address $deliveryAddress,
        public readonly Contact $deliveryContact,
        public readonly array $parcels,
        public readonly ServiceLevel $serviceLevelCode,
        public readonly ?string $customerReference = null,
        public readonly ?string $customerReferenceName = null,
        public readonly ?string $customTrackingReference = null,
        public readonly ?int $declaredValue = null,
        public readonly ?string $specialInstructionsCollection = null,
        public readonly ?string $specialInstructionsDelivery = null,
        public readonly ?string $collectionMinDate = null,
        public readonly ?string $deliveryMinDate = null,
        public readonly ?string $collectionAfter = null,
        public readonly ?string $collectionBefore = null,
        public readonly ?string $deliveryAfter = null,
        public readonly ?string $deliveryBefore = null,
        public readonly bool $muteNotifications = false,
    ) {
        if (count($this->parcels) === 0) {
            throw new InvalidArgumentException('ShipmentRequest must contain at least one parcel');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'collection_address' => $this->collectionAddress->toArray(),
            'collection_contact' => $this->collectionContact->toArray(),
            'delivery_address' => $this->deliveryAddress->toArray(),
            'delivery_contact' => $this->deliveryContact->toArray(),
            'parcels' => array_map(fn (Parcel $p): array => $p->toArray(), $this->parcels),
            'service_level_code' => $this->serviceLevelCode->value,
            'mute_notifications' => $this->muteNotifications,
        ];

        $optional = [
            'customer_reference' => $this->customerReference,
            'customer_reference_name' => $this->customerReferenceName,
            'custom_tracking_reference' => $this->customTrackingReference,
            'declared_value' => $this->declaredValue,
            'special_instructions_collection' => $this->specialInstructionsCollection,
            'special_instructions_delivery' => $this->specialInstructionsDelivery,
            'collection_min_date' => $this->collectionMinDate,
            'delivery_min_date' => $this->deliveryMinDate,
            'collection_after' => $this->collectionAfter,
            'collection_before' => $this->collectionBefore,
            'delivery_after' => $this->deliveryAfter,
            'delivery_before' => $this->deliveryBefore,
        ];

        foreach ($optional as $key => $value) {
            if ($value !== null) {
                $payload[$key] = $value;
            }
        }

        return $payload;
    }
}
