<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ValueObjects;

use Sonnenglas\Shiplogic\Enums\AddressType;
use Sonnenglas\Shiplogic\Exceptions\InvalidArgumentException;

class Address
{
    public function __construct(
        public readonly string $streetAddress,
        public readonly string $city,
        public readonly string $zone,
        public readonly string $country,
        public readonly string $code,
        public readonly string $localArea = '',
        public readonly ?string $company = null,
        public readonly AddressType $type = AddressType::Unknown,
        public readonly ?float $lat = null,
        public readonly ?float $lng = null,
    ) {
        if ($this->streetAddress === '') {
            throw new InvalidArgumentException('Address street_address must not be empty');
        }

        if ($this->city === '') {
            throw new InvalidArgumentException('Address city must not be empty');
        }

        if (strlen($this->country) !== 2) {
            throw new InvalidArgumentException('Address country must be a 2-letter ISO code (e.g. "ZA")');
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'street_address' => $this->streetAddress,
            'local_area' => $this->localArea,
            'city' => $this->city,
            'zone' => $this->zone,
            'country' => $this->country,
            'code' => $this->code,
            'type' => $this->type->value,
        ];

        if ($this->company !== null) {
            $payload['company'] = $this->company;
        }

        if ($this->lat !== null) {
            $payload['lat'] = $this->lat;
        }

        if ($this->lng !== null) {
            $payload['lng'] = $this->lng;
        }

        return $payload;
    }
}
