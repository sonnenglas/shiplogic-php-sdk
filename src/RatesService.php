<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic;

use Sonnenglas\Shiplogic\ResponseParsers\RatesResponseParser;
use Sonnenglas\Shiplogic\Traits\HasLastRawResponse;
use Sonnenglas\Shiplogic\ValueObjects\RateRequest;

class RatesService
{
    use HasLastRawResponse;

    protected RatesResponseParser $parser;

    public function __construct(
        protected Client $client,
        ?RatesResponseParser $parser = null,
    ) {
        $this->parser = $parser ?? new RatesResponseParser();
    }

    /**
     * @return list<\Sonnenglas\Shiplogic\Responses\RateQuote>
     */
    public function getRates(RateRequest $request): array
    {
        $payload = $this->client->post('rates', $request->toArray());
        $this->lastRawResponse = $payload;

        return $this->parser->parse($payload);
    }
}
