<?php

declare(strict_types=1);

namespace Tests\Unit\Webhooks;

use PHPUnit\Framework\TestCase;
use Sonnenglas\Shiplogic\Webhooks\TrackingEventPayload;

class TrackingEventPayloadTest extends TestCase
{
    public function test_parses_real_webhook_payload(): void
    {
        $raw = json_decode($this->sampleWebhook(), true);
        $payload = TrackingEventPayload::fromArray($raw);

        $this->assertSame(108639, $payload->shipmentId);
        $this->assertSame('SLXS7GL', $payload->customTrackingReference);
        $this->assertSame('S7GL', $payload->shortTrackingReference);
        $this->assertSame('at-hub', $payload->status);
        $this->assertSame('ECO', $payload->serviceLevelCode);
        $this->assertCount(4, $payload->trackingEvents);
        $this->assertSame('at-hub', $payload->trackingEvents[0]->status);
    }

    protected function sampleWebhook(): string
    {
        return <<<'JSON'
{
  "collection_hub": "JHB",
  "custom_tracking_reference": "SLXS7GL",
  "event_time": "2025-06-17T14:23:30.493767538+02:00",
  "service_level_code": "ECO",
  "shipment_id": 108639,
  "short_tracking_reference": "S7GL",
  "status": "at-hub",
  "tracking_events": [
    {"id": 418146, "date": "2025-06-17T12:23:30.493767Z", "location": "PTA", "message": "At PTA hub", "parcel_id": 0, "source": "mariskaadmin", "status": "at-hub"},
    {"id": 418141, "date": "2025-06-17T12:22:32.632101Z", "message": "", "parcel_id": 0, "source": "corneldriver", "status": "collected"},
    {"id": 418068, "date": "2025-06-17T09:16:51.391089Z", "message": "", "parcel_id": 0, "source": "stephanops", "status": "collection-assigned"},
    {"id": 418067, "date": "2025-06-17T09:16:51.376125Z", "message": "", "parcel_id": 0, "source": "system", "status": "submitted"}
  ],
  "update_type": "shipment"
}
JSON;
    }
}
