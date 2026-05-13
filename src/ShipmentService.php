<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic;

use Sonnenglas\Shiplogic\Exceptions\ShiplogicApiException;
use Sonnenglas\Shiplogic\ResponseParsers\ShipmentResponseParser;
use Sonnenglas\Shiplogic\Responses\CancellationResponse;
use Sonnenglas\Shiplogic\Responses\LabelResponse;
use Sonnenglas\Shiplogic\Responses\ShipmentResponse;
use Sonnenglas\Shiplogic\Traits\HasLastRawResponse;
use Sonnenglas\Shiplogic\ValueObjects\ShipmentRequest;

class ShipmentService
{
    use HasLastRawResponse;

    protected ShipmentResponseParser $parser;

    public function __construct(
        protected Client $client,
        ?ShipmentResponseParser $parser = null,
    ) {
        $this->parser = $parser ?? new ShipmentResponseParser();
    }

    public function createShipment(ShipmentRequest $request): ShipmentResponse
    {
        $payload = $this->client->post('shipments', $request->toArray());
        $this->lastRawResponse = $payload;

        /** @var array<string, mixed> $payload */
        return $this->parser->parse($payload);
    }

    public function getShipmentByTrackingReference(string $trackingReference): ShipmentResponse
    {
        $payload = $this->client->get('shipments', ['tracking_reference' => $trackingReference]);
        $this->lastRawResponse = $payload;

        $shipment = $this->extractFirstShipment($payload);

        return $this->parser->parse($shipment);
    }

    public function getShipmentById(int $id): ShipmentResponse
    {
        $payload = $this->client->get('shipments', ['id' => $id]);
        $this->lastRawResponse = $payload;

        $shipment = $this->extractFirstShipment($payload);

        return $this->parser->parse($shipment);
    }

    public function getLabel(int $shipmentId): LabelResponse
    {
        $payload = $this->client->get('shipments/label', ['id' => $shipmentId]);
        $this->lastRawResponse = $payload;

        $url = $payload['url'] ?? ($payload[0] ?? null);

        if (! is_string($url) || $url === '') {
            throw new ShiplogicApiException('Shiplogic did not return a label URL for shipment '.$shipmentId);
        }

        return new LabelResponse(url: $url);
    }

    public function getStickerLabel(int $shipmentId): LabelResponse
    {
        $payload = $this->client->get('shipments/label/stickers', ['id' => $shipmentId]);
        $this->lastRawResponse = $payload;

        $url = $payload['url'] ?? ($payload[0] ?? null);

        if (! is_string($url) || $url === '') {
            throw new ShiplogicApiException('Shiplogic did not return a sticker label URL for shipment '.$shipmentId);
        }

        return new LabelResponse(url: $url);
    }

    public function cancelShipment(string $trackingReference): CancellationResponse
    {
        try {
            $payload = $this->client->post('shipments/cancel', ['tracking_reference' => $trackingReference]);
            $this->lastRawResponse = $payload;

            return new CancellationResponse(
                success: true,
                trackingReference: $trackingReference,
            );
        } catch (ShiplogicApiException $e) {
            $this->lastErrorResponse = $e->getResponseBody();

            return new CancellationResponse(
                success: false,
                trackingReference: $trackingReference,
                error: $e->getMessage(),
            );
        }
    }

    /**
     * @param  array<string, mixed>|list<mixed>  $payload
     * @return array<string, mixed>
     */
    protected function extractFirstShipment(array $payload): array
    {
        if (isset($payload['shipments']) && is_array($payload['shipments'])) {
            $shipments = $payload['shipments'];

            if (count($shipments) === 0) {
                throw new ShiplogicApiException('Shiplogic returned no shipments');
            }

            $first = reset($shipments);

            if (! is_array($first)) {
                throw new ShiplogicApiException('Shiplogic returned malformed shipment payload');
            }

            return $first;
        }

        if (isset($payload['id'])) {
            /** @var array<string, mixed> $payload */
            return $payload;
        }

        throw new ShiplogicApiException('Shiplogic returned no recognizable shipment payload');
    }
}
