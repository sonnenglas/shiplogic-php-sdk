<?php

declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Sonnenglas\Shiplogic\Client;
use Sonnenglas\Shiplogic\Enums\AddressType;
use Sonnenglas\Shiplogic\RatesService;
use Sonnenglas\Shiplogic\ValueObjects\Address;
use Sonnenglas\Shiplogic\ValueObjects\Parcel;
use Sonnenglas\Shiplogic\ValueObjects\RateRequest;

class RatesServiceTest extends TestCase
{
    public function test_get_rates_returns_quotes(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('rates_response.json')));

        $service = new RatesService(new Client('t', httpClient: $this->http));
        $quotes = $service->getRates($this->sampleRequest());

        $this->assertCount(2, $quotes);
        $this->assertSame('ECO', $quotes[0]->serviceLevelCode);
        $this->assertSame(92.5, $quotes[0]->rate);
        $this->assertSame('OVN', $quotes[1]->serviceLevelCode);
        $this->assertSame(215.0, $quotes[1]->rate);
    }

    public function test_get_rates_posts_to_rates_endpoint(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('rates_response.json')));

        $service = new RatesService(new Client('t', httpClient: $this->http));
        $service->getRates($this->sampleRequest());

        $request = $this->mock->getLastRequest();
        $this->assertNotNull($request);
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringEndsWith('rates', (string) $request->getUri());
    }

    protected function sampleRequest(): RateRequest
    {
        $address = new Address(
            streetAddress: '123 Test Street',
            city: 'Pretoria',
            zone: 'Gauteng',
            country: 'ZA',
            code: '0001',
            type: AddressType::Business,
        );

        return new RateRequest(
            collectionAddress: $address,
            deliveryAddress: $address,
            parcels: [new Parcel(lengthCm: 30.0, widthCm: 20.0, heightCm: 10.0, weightKg: 2.0)],
            declaredValue: 1500,
        );
    }
}
