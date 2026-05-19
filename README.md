# Shiplogic PHP SDK

Unofficial PHP SDK for the [Shiplogic](https://shiplogic.com) shipping platform — the API behind **The Courier Guy** and other South African carrier brands.

Covers rate quotes, shipment CRUD, label and sticker PDF downloads, cancellation, tracking events, and inbound webhook payloads (`tracking-event`, `shipment-note`, `parcel-dimension-changes`, `shipment-address-changes`). Hand-crafted, dependency-light (Guzzle only), with first-class value objects and response DTOs.

## Requirements

- PHP 8.1+
- ext-json
- `guzzlehttp/guzzle` ^7.1

## Installation

```bash
composer require sonnenglas/shiplogic-sdk
```

## Usage

```php
use Sonnenglas\Shiplogic\Shiplogic;
use Sonnenglas\Shiplogic\ValueObjects\Address;
use Sonnenglas\Shiplogic\ValueObjects\Contact;
use Sonnenglas\Shiplogic\ValueObjects\Parcel;
use Sonnenglas\Shiplogic\ValueObjects\ShipmentRequest;
use Sonnenglas\Shiplogic\Enums\ServiceLevel;
use Sonnenglas\Shiplogic\Enums\AddressType;

// Shiplogic uses a single API host (api.shiplogic.com); sandbox vs production
// is decided by the API token, not the URL. `productionMode` is kept for
// backwards compatibility but no longer changes the base URI.
$shiplogic = new Shiplogic(apiToken: '...');
$shipmentService = $shiplogic->getShipmentService();

$sender = new Address(
    streetAddress: '16 Viljoen Street',
    localArea: 'Lorentzville',
    city: 'Johannesburg',
    zone: 'GP',
    country: 'ZA',
    code: '2094',
    company: 'Sonnenglas',
    type: AddressType::Business,
);

$recipient = new Address(
    streetAddress: '10 Midas Avenue',
    localArea: 'Olympus AH',
    city: 'Pretoria',
    zone: 'GP',
    country: 'ZA',
    code: '0081',
    type: AddressType::Residential,
);

$parcel = new Parcel(lengthCm: 30.0, widthCm: 20.0, heightCm: 10.0, weightKg: 2.0);

$request = new ShipmentRequest(
    collectionAddress: $sender,
    collectionContact: new Contact(name: 'Warehouse Op', mobileNumber: '+27110000000', email: 'ops@example.com'),
    deliveryAddress: $recipient,
    deliveryContact: new Contact(name: 'Jane Doe', mobileNumber: '+27821234567', email: 'jane@example.com'),
    parcels: [$parcel],
    serviceLevelCode: ServiceLevel::Economy,
    customerReference: 'ORDER-123',
);

$response = $shipmentService->createShipment($request);

echo $response->shortTrackingReference;        // e.g. "3BM3"
echo $response->customTrackingReference;       // e.g. "SLX3BM3"

$label = $shipmentService->getLabel($response->id);
echo $label->url;                              // signed S3 URL, valid 24h
```

## Services

The `Shiplogic` facade exposes three services:

- `getShipmentService()` — create / fetch / cancel shipments, download label PDFs and sticker labels.
- `getRatesService()` — fetch rate quotes for a collection/delivery pair.
- `getTrackingService()` — fetch a shipment's status and event timeline.

## Webhooks

Shiplogic delivers webhooks for `tracking-event`, `shipment-note`, `parcel-dimension-changes`, and `shipment-address-changes` topics. The SDK ships first-class payload DTOs under `Sonnenglas\Shiplogic\Webhooks\*` so consumers can hydrate inbound payloads without re-parsing JSON.

```php
use Sonnenglas\Shiplogic\Webhooks\TrackingEventPayload;

$payload = TrackingEventPayload::fromArray(json_decode($request->getBody(), true));
```

## Testing

```bash
composer install
./vendor/bin/phpunit --testsuite Unit
```

Integration tests are gated on a sandbox token in `tests/.env`:

```bash
SHIPLOGIC_SANDBOX_TOKEN=your-sandbox-token ./vendor/bin/phpunit --testsuite Integration
```

## License

MIT. See LICENSE.
