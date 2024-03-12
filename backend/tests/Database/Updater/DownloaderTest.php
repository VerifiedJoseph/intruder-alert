<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Database\Updater\Downloader;
use IntruderAlert\Exception\FetchException;

class DownloaderTest extends TestCase
{
    /**
     * Test `getChecksum()`
     */
    public function testGetChecksum(): void
    {
        $this->expectOutputRegex('/Downloading checksum/');

        $expected = hash('sha256', 'hello word');

        $fetch = $this->createStub(Fetch::class);
        $fetch->method('get')->willReturn($expected);

        $downloader = new Downloader($fetch, $this->createConfigStub());
        $actual = $downloader->getChecksum('GeoLite2-ASN');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test `getChecksum()` failure
     */
    public function testGetChecksumFailure(): void
    {
        $this->expectOutputRegex('/Downloading checksum/');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to download checksum file');

        $fetch = $this->createStub(Fetch::class);
        $fetch->method('get')->willThrowException(new FetchException('Request failed'));

        $downloader = new Downloader($fetch, $this->createConfigStub());
        $downloader->getChecksum('GeoLite2-ASN');
    }

    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getMaxMindLicenseKey')->willReturn('qwerty-qwerty');
        return $config;
    }
}
