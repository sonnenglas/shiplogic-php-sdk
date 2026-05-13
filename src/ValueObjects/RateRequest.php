<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ValueObjects;

use Sonnenglas\Shiplogic\Exceptions\InvalidArgumentException;

class RateRequest
{
    /**
     * @param  list<Parcel>  $parcels
     */
    public function __construct(
        public readonly Address $collectionAddress,
        public readonly Address $deliveryAddress,
        public readonly array $parcels,
        public readonly ?int $declaredValue = null,
        public readonly ?string $collectionMinDate = null,
        public readonly ?string $deliveryMinDate = null,
    ) {
        if (count($this->parcels) === 0) {
            throw new InvalidArgumentException('RateRequest must contain at least one parcel');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'collection_address' => $this->collectionAddress->toArray(),
            'delivery_address' => $this->deliveryAddress->toArray(),
            'parcels' => array_map(fn (Parcel $p): array => $p->toArray(), $this->parcels),
        ];

        if ($this->declaredValue !== null) {
            $payload['declared_value'] = $this->declaredValue;
        }

        if ($this->collectionMinDate !== null) {
            $payload['collection_min_date'] = $this->collectionMinDate;
        }

        if ($this->deliveryMinDate !== null) {
            $payload['delivery_min_date'] = $this->deliveryMinDate;
        }

        return $payload;
    }
}
