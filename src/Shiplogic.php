<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic;

use GuzzleHttp\ClientInterface;

class Shiplogic
{
    protected Client $client;

    protected ?ShipmentService $shipmentService = null;

    protected ?RatesService $ratesService = null;

    protected ?TrackingService $trackingService = null;

    public function __construct(
        string $apiToken,
        bool $productionMode = false,
        ?string $baseUriOverride = null,
        ?ClientInterface $httpClient = null,
    ) {
        $this->client = new Client($apiToken, $productionMode, $baseUriOverride, $httpClient);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getShipmentService(): ShipmentService
    {
        return $this->shipmentService ??= new ShipmentService($this->client);
    }

    public function getRatesService(): RatesService
    {
        return $this->ratesService ??= new RatesService($this->client);
    }

    public function getTrackingService(): TrackingService
    {
        return $this->trackingService ??= new TrackingService($this->client);
    }
}
