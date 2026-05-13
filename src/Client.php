<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Sonnenglas\Shiplogic\Exceptions\AuthenticationException;
use Sonnenglas\Shiplogic\Exceptions\RateLimitException;
use Sonnenglas\Shiplogic\Exceptions\ShiplogicApiException;
use Sonnenglas\Shiplogic\Exceptions\ValidationException;

class Client
{
    public const URI_PRODUCTION = 'https://api.shiplogic.com/';

    public const URI_SANDBOX = 'https://sandbox.shiplogic.com/';

    protected string $baseUri;

    protected ClientInterface $httpClient;

    public function __construct(
        protected string $apiToken,
        protected bool $productionMode = false,
        ?string $baseUriOverride = null,
        ?ClientInterface $httpClient = null,
    ) {
        $this->baseUri = $baseUriOverride
            ?? ($this->productionMode ? self::URI_PRODUCTION : self::URI_SANDBOX);

        $this->httpClient = $httpClient ?? new GuzzleClient();
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function setHttpClient(ClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>|list<mixed>
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->request('GET', $uri, ['query' => $query]);
    }

    /**
     * @param  array<string, mixed>|list<mixed>  $body
     * @return array<string, mixed>|list<mixed>
     */
    public function post(string $uri, array $body = []): array
    {
        return $this->request('POST', $uri, ['json' => $body]);
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>|list<mixed>
     */
    public function delete(string $uri, array $body = []): array
    {
        return $this->request('DELETE', $uri, ['json' => $body]);
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>|list<mixed>
     */
    protected function request(string $method, string $uri, array $options): array
    {
        $options = array_merge_recursive($options, [
            'base_uri' => $this->baseUri,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiToken,
                'Accept' => 'application/json',
            ],
        ]);

        try {
            $response = $this->httpClient->request($method, $uri, $options);
        } catch (GuzzleClientException $e) {
            $this->throwForClientError($e);
        } catch (ServerException $e) {
            throw new ShiplogicApiException(
                message: 'Shiplogic API server error: '.$e->getMessage(),
                statusCode: $e->getResponse()->getStatusCode(),
                responseBody: (string) $e->getResponse()->getBody(),
                previous: $e,
            );
        } catch (GuzzleException $e) {
            throw new ShiplogicApiException(
                message: 'Shiplogic API request failed: '.$e->getMessage(),
                previous: $e,
            );
        }

        $body = (string) $response->getBody();

        if ($body === '') {
            return [];
        }

        $decoded = json_decode($body, true);

        if (! is_array($decoded)) {
            throw new ShiplogicApiException('Shiplogic API returned non-JSON response: '.substr($body, 0, 200));
        }

        return $decoded;
    }

    protected function throwForClientError(GuzzleClientException $e): never
    {
        $status = $e->getResponse()->getStatusCode();
        $body = (string) $e->getResponse()->getBody();
        $message = $this->extractApiMessage($body) ?? $e->getMessage();

        match (true) {
            $status === 401 || $status === 403 => throw new AuthenticationException($message, $status, $body, $e),
            $status === 429 => throw new RateLimitException($message, $status, $body, $e),
            $status === 400 || $status === 422 => throw new ValidationException($message, $status, $body, $e),
            default => throw new ShiplogicApiException($message, $status, $body, $e),
        };
    }

    protected function extractApiMessage(string $body): ?string
    {
        $decoded = json_decode($body, true);

        if (! is_array($decoded)) {
            return null;
        }

        if (isset($decoded['message']) && is_string($decoded['message'])) {
            return $decoded['message'];
        }

        if (isset($decoded['error']) && is_string($decoded['error'])) {
            return $decoded['error'];
        }

        return null;
    }
}
