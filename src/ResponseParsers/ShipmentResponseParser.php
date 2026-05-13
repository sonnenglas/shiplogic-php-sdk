<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ResponseParsers;

use Sonnenglas\Shiplogic\Exceptions\ShiplogicApiException;
use Sonnenglas\Shiplogic\Responses\ShipmentResponse;

class ShipmentResponseParser
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function parse(array $payload): ShipmentResponse
    {
        if (! isset($payload['id'])) {
            throw new ShiplogicApiException('Shipment response missing "id" field');
        }

        return new ShipmentResponse(
            id: (int) $payload['id'],
            shortTrackingReference: (string) ($payload['short_tracking_reference'] ?? ''),
            customTrackingReference: (string) ($payload['custom_tracking_reference'] ?? ''),
            status: (string) ($payload['status'] ?? ''),
            serviceLevelCode: (string) ($payload['service_level_code'] ?? ''),
            serviceLevelName: isset($payload['service_level_name']) ? (string) $payload['service_level_name'] : null,
            rate: (float) ($payload['rate'] ?? 0.0),
            timeCreated: isset($payload['time_created']) ? (string) $payload['time_created'] : null,
            raw: $payload,
        );
    }
}
