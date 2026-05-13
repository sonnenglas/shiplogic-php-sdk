<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic;

use Sonnenglas\Shiplogic\ResponseParsers\TrackingResponseParser;
use Sonnenglas\Shiplogic\Responses\TrackingResponse;
use Sonnenglas\Shiplogic\Traits\HasLastRawResponse;

class TrackingService
{
    use HasLastRawResponse;

    protected TrackingResponseParser $parser;

    public function __construct(
        protected Client $client,
        ?TrackingResponseParser $parser = null,
    ) {
        $this->parser = $parser ?? new TrackingResponseParser();
    }

    public function getTracking(string $trackingReference): TrackingResponse
    {
        $payload = $this->client->get('tracking/shipments', ['tracking_reference' => $trackingReference]);
        $this->lastRawResponse = $payload;

        return $this->parser->parse($payload);
    }
}
