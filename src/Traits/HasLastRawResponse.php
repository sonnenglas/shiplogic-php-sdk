<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Traits;

trait HasLastRawResponse
{
    /** @var array<string, mixed>|null */
    protected ?array $lastRawResponse = null;

    protected ?string $lastErrorResponse = null;

    /**
     * @return array<string, mixed>|null
     */
    public function getLastRawResponse(): ?array
    {
        return $this->lastRawResponse;
    }

    public function getLastErrorResponse(): ?string
    {
        return $this->lastErrorResponse;
    }
}
