<?php

declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Sonnenglas\Shiplogic\Client;
use Sonnenglas\Shiplogic\Exceptions\AuthenticationException;
use Sonnenglas\Shiplogic\Exceptions\RateLimitException;
use Sonnenglas\Shiplogic\Exceptions\ShiplogicApiException;
use Sonnenglas\Shiplogic\Exceptions\ValidationException;

class ClientTest extends TestCase
{
    public function test_uses_sandbox_uri_by_default(): void
    {
        $client = new Client('test-token');

        $this->assertSame(Client::URI_SANDBOX, $client->getBaseUri());
    }

    public function test_uses_production_uri_when_production_mode(): void
    {
        $client = new Client('test-token', productionMode: true);

        $this->assertSame(Client::URI_PRODUCTION, $client->getBaseUri());
    }

    public function test_uses_base_uri_override(): void
    {
        $client = new Client('test-token', baseUriOverride: 'https://example.test/');

        $this->assertSame('https://example.test/', $client->getBaseUri());
    }

    public function test_sends_bearer_token_header(): void
    {
        $this->mock->append(new Response(200, [], '{"ok":true}'));

        $client = new Client('sample-token', httpClient: $this->http);
        $client->get('shipments', []);

        $request = $this->mock->getLastRequest();
        $this->assertNotNull($request);
        $this->assertSame('Bearer sample-token', $request->getHeaderLine('Authorization'));
    }

    public function test_decodes_json_response(): void
    {
        $this->mock->append(new Response(200, [], '{"foo":"bar","count":3}'));

        $client = new Client('t', httpClient: $this->http);
        $result = $client->get('shipments', []);

        $this->assertSame(['foo' => 'bar', 'count' => 3], $result);
    }

    public function test_throws_authentication_exception_on_401(): void
    {
        $this->mock->append(new Response(401, [], '{"message":"Error logging in"}'));

        $client = new Client('t', httpClient: $this->http);

        $this->expectException(AuthenticationException::class);
        $client->get('shipments', []);
    }

    public function test_throws_rate_limit_exception_on_429(): void
    {
        $this->mock->append(new Response(429, [], '{"message":"Too many requests"}'));

        $client = new Client('t', httpClient: $this->http);

        $this->expectException(RateLimitException::class);
        $client->get('shipments', []);
    }

    public function test_throws_validation_exception_on_422(): void
    {
        $this->mock->append(new Response(422, [], '{"message":"Validation failed"}'));

        $client = new Client('t', httpClient: $this->http);

        $this->expectException(ValidationException::class);
        $client->post('shipments', []);
    }

    public function test_throws_generic_api_exception_on_500(): void
    {
        $this->mock->append(new Response(500, [], '{"message":"oops"}'));

        $client = new Client('t', httpClient: $this->http);

        $this->expectException(ShiplogicApiException::class);
        $client->get('shipments', []);
    }
}
