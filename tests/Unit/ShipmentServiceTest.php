<?php

declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Sonnenglas\Shiplogic\Client;
use Sonnenglas\Shiplogic\Enums\AddressType;
use Sonnenglas\Shiplogic\Enums\ServiceLevel;
use Sonnenglas\Shiplogic\ShipmentService;
use Sonnenglas\Shiplogic\ValueObjects\Address;
use Sonnenglas\Shiplogic\ValueObjects\Contact;
use Sonnenglas\Shiplogic\ValueObjects\Parcel;
use Sonnenglas\Shiplogic\ValueObjects\ShipmentRequest;

class ShipmentServiceTest extends TestCase
{
    public function test_create_shipment_returns_parsed_response(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('shipment_create_response.json')));

        $service = new ShipmentService(new Client('t', httpClient: $this->http));
        $response = $service->createShipment($this->sampleRequest());

        $this->assertSame(108649, $response->id);
        $this->assertSame('SLX3BM3', $response->customTrackingReference);
        $this->assertSame('3BM3', $response->shortTrackingReference);
        $this->assertSame('ECO', $response->serviceLevelCode);
        $this->assertSame(20.5, $response->rate);
        $this->assertArrayHasKey('parcels', $response->raw);
    }

    public function test_create_shipment_posts_payload_to_shipments_endpoint(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('shipment_create_response.json')));

        $service = new ShipmentService(new Client('t', httpClient: $this->http));
        $service->createShipment($this->sampleRequest());

        $request = $this->mock->getLastRequest();
        $this->assertNotNull($request);
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringEndsWith('shipments', (string) $request->getUri());

        $body = json_decode((string) $request->getBody(), true);
        $this->assertSame('ECO', $body['service_level_code']);
        $this->assertSame('123 Test Street', $body['collection_address']['street_address']);
    }

    public function test_get_label_returns_signed_url(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('label_response.json')));

        $service = new ShipmentService(new Client('t', httpClient: $this->http));
        $label = $service->getLabel(108649);

        $this->assertStringStartsWith('https://s3', $label->url);
    }

    public function test_cancel_shipment_returns_success_on_2xx(): void
    {
        $this->mock->append(new Response(204, [], ''));

        $service = new ShipmentService(new Client('t', httpClient: $this->http));
        $result = $service->cancelShipment('SLX3BM3');

        $this->assertTrue($result->success);
        $this->assertSame('SLX3BM3', $result->trackingReference);
    }

    public function test_cancel_shipment_returns_failure_on_4xx(): void
    {
        $this->mock->append(new Response(400, [], '{"message":"Already collected"}'));

        $service = new ShipmentService(new Client('t', httpClient: $this->http));
        $result = $service->cancelShipment('SLX3BM3');

        $this->assertFalse($result->success);
        $this->assertSame('SLX3BM3', $result->trackingReference);
        $this->assertNotNull($result->error);
    }

    protected function sampleRequest(): ShipmentRequest
    {
        $address = new Address(
            streetAddress: '123 Test Street',
            city: 'Pretoria',
            zone: 'Gauteng',
            country: 'ZA',
            code: '0001',
            type: AddressType::Business,
        );

        $contact = new Contact(name: 'Test User', mobileNumber: '+27110000000', email: 'a@b.test');

        $parcel = new Parcel(lengthCm: 30.0, widthCm: 20.0, heightCm: 10.0, weightKg: 2.0);

        return new ShipmentRequest(
            collectionAddress: $address,
            collectionContact: $contact,
            deliveryAddress: $address,
            deliveryContact: $contact,
            parcels: [$parcel],
            serviceLevelCode: ServiceLevel::Economy,
            customerReference: 'ORDER-1',
        );
    }
}
