<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

use PHPUnit\Framework\TestCase;
use Sonnenglas\Shiplogic\Enums\AddressType;
use Sonnenglas\Shiplogic\Exceptions\InvalidArgumentException;
use Sonnenglas\Shiplogic\ValueObjects\Address;

class AddressTest extends TestCase
{
    public function test_serializes_required_fields(): void
    {
        $address = new Address(
            streetAddress: '194 Bancor Avenue',
            city: 'Pretoria',
            zone: 'Gauteng',
            country: 'ZA',
            code: '0181',
            localArea: 'Menlyn',
            type: AddressType::Business,
        );

        $array = $address->toArray();

        $this->assertSame('194 Bancor Avenue', $array['street_address']);
        $this->assertSame('Menlyn', $array['local_area']);
        $this->assertSame('Pretoria', $array['city']);
        $this->assertSame('ZA', $array['country']);
        $this->assertSame('business', $array['type']);
    }

    public function test_omits_optional_fields_when_null(): void
    {
        $address = new Address(
            streetAddress: '194 Bancor Avenue',
            city: 'Pretoria',
            zone: 'Gauteng',
            country: 'ZA',
            code: '0181',
        );

        $array = $address->toArray();

        $this->assertArrayNotHasKey('company', $array);
        $this->assertArrayNotHasKey('lat', $array);
        $this->assertArrayNotHasKey('lng', $array);
    }

    public function test_includes_coordinates_when_set(): void
    {
        $address = new Address(
            streetAddress: '194 Bancor Avenue',
            city: 'Pretoria',
            zone: 'Gauteng',
            country: 'ZA',
            code: '0181',
            lat: -25.78,
            lng: 28.27,
        );

        $array = $address->toArray();

        $this->assertSame(-25.78, $array['lat']);
        $this->assertSame(28.27, $array['lng']);
    }

    public function test_rejects_empty_street_address(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Address(streetAddress: '', city: 'Pretoria', zone: 'GP', country: 'ZA', code: '0001');
    }

    public function test_rejects_invalid_country_code(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Address(streetAddress: '1 Test', city: 'Pretoria', zone: 'GP', country: 'ZAA', code: '0001');
    }
}
