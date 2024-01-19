<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Fetch;
use IntruderAlert\Exception\FetchException;

class FetchTest extends TestCase
{
    /** @var string $useragent HTTP useragent */
    private string $useragent = 'PHPUnit/FetchTest';

    /**
     * Test `get()` method
     */
    public function testGet(): void
    {
        $fetch = new Fetch($this->useragent);
        $data = $fetch->get('https://httpbingo.org/get');

        /** @var stdClass $response */
        $response = json_decode($data);

        $this->assertIsObject($response);
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

        $fetch = new Fetch($this->useragent);
        $data = $fetch->get('https://httpbingo.org/status/404');
    }

    /**
     * Test `get()` method with an invalid URL
     */
    public function testGetWithInvalidUrl(): void
    {
        $this->expectException(FetchException::class);
        $this->expectExceptionMessage('Could not resolve host: example.invalid');

        $fetch = new Fetch($this->useragent);
        $fetch->get('https://example.invalid');
    }
}
