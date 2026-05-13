<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ResponseParsers;

use Sonnenglas\Shiplogic\Responses\RateQuote;

class RatesResponseParser
{
    /**
     * @param  array<string, mixed>|list<mixed>  $payload
     * @return list<RateQuote>
     */
    public function parse(array $payload): array
    {
        $rates = $payload['rates'] ?? $payload;

        if (! is_array($rates)) {
            return [];
        }

        $quotes = [];

        foreach ($rates as $rate) {
            if (! is_array($rate)) {
                continue;
            }

            $quotes[] = new RateQuote(
                serviceLevelCode: (string) ($rate['service_level']['code'] ?? $rate['service_level_code'] ?? ''),
                serviceLevelName: (string) ($rate['service_level']['name'] ?? $rate['service_level_name'] ?? ''),
                serviceLevelId: (int) ($rate['service_level']['id'] ?? $rate['service_level_id'] ?? 0),
                rate: (float) ($rate['rate'] ?? 0.0),
                estimatedCollection: isset($rate['service_level']['collection_date']) ? (string) $rate['service_level']['collection_date'] : (isset($rate['estimated_collection']) ? (string) $rate['estimated_collection'] : null),
                estimatedDeliveryFrom: isset($rate['service_level']['delivery_date_from']) ? (string) $rate['service_level']['delivery_date_from'] : (isset($rate['estimated_delivery_from']) ? (string) $rate['estimated_delivery_from'] : null),
                estimatedDeliveryTo: isset($rate['service_level']['delivery_date_to']) ? (string) $rate['service_level']['delivery_date_to'] : (isset($rate['estimated_delivery_to']) ? (string) $rate['estimated_delivery_to'] : null),
                raw: $rate,
            );
        }

        return $quotes;
    }
}
