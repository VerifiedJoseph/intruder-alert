<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Exception\FetchException;

#[CoversClass(Fetch::class)]
#[UsesClass(Logger::class)]
class FetchTest extends AbstractTestCase
{
    /** @var string $useragent HTTP useragent */
    private string $useragent = 'PHPUnit/FetchTest';

    /**
     * Test `get()` method
     */
    public function testGet(): void
    {
        $fetch = new Fetch($this->useragent, self::$logger);
        $data = $fetch->get('https://httpbingo.org/get');

        /** @var stdClass $response */
        $response = json_decode($data);

        $this->assertEquals($this->useragent, $response->headers->{'User-Agent'}[0]);
        $this->assertEquals('GET', $response->method);
    }

    /**
     * Test `get()` method with HTTP 404 response
     */
    public function testGetWithHttp404Response(): void
    {
        $this->expectException(FetchException::class);
        $this->expectExceptionMessage('Request failed. Returned HTTP 404');

        $fetch = new Fetch($this->useragent, self::$logger);
        $data = $fetch->get('https://httpbingo.org/status/404');
    }

    /**
     * Test `get()` method with an invalid URL
     */
    public function testGetWithInvalidUrl(): void
    {
        $this->expectException(FetchException::class);
        $this->expectExceptionMessage('Could not resolve host: example.invalid');

        $fetch = new Fetch($this->useragent, self::$logger);
        $fetch->get('https://example.invalid');
    }
}
