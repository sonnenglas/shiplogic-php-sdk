<?php

declare(strict_types=1);

namespace Tests\Unit;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected MockHandler $mock;

    protected GuzzleClient $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = new MockHandler();
        $stack = HandlerStack::create($this->mock);
        $this->http = new GuzzleClient(['handler' => $stack]);
    }

    protected function fixture(string $name): string
    {
        $path = __DIR__.'/../fixtures/'.$name;

        if (! is_file($path)) {
            throw new \RuntimeException("Fixture not found: {$path}");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new \RuntimeException("Failed to read fixture: {$path}");
        }

        return $contents;
    }
}
