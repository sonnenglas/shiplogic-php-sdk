<?php

declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Sonnenglas\Shiplogic\Client;
use Sonnenglas\Shiplogic\TrackingService;

class TrackingServiceTest extends TestCase
{
    public function test_get_tracking_returns_status_and_events(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('tracking_response.json')));

        $service = new TrackingService(new Client('t', httpClient: $this->http));
        $response = $service->getTracking('SLXS7GL');

        $this->assertSame('at-hub', $response->status);
        $this->assertSame('SLXS7GL', $response->customTrackingReference);
        $this->assertCount(2, $response->trackingEvents);
        $this->assertSame('at-hub', $response->trackingEvents[0]->status);
        $this->assertSame('collected', $response->trackingEvents[1]->status);
    }

    public function test_get_tracking_uses_tracking_endpoint(): void
    {
        $this->mock->append(new Response(200, [], $this->fixture('tracking_response.json')));

        $service = new TrackingService(new Client('t', httpClient: $this->http));
        $service->getTracking('SLXS7GL');

        $request = $this->mock->getLastRequest();
        $this->assertNotNull($request);
        $uri = (string) $request->getUri();
        $this->assertStringContainsString('tracking/shipments', $uri);
        $this->assertStringContainsString('tracking_reference=SLXS7GL', $uri);
    }
}
