<?php

use PHPUnit\Framework\TestCase;
use MockFileSystem\MockFileSystem as mockfs;
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

    /**
     * Test `getArchive()`
     */
    public function testGetArchive(): void
    {
        $this->expectOutputRegex('/Downloading database/');

        mockfs::create();
        $file = mockfs::getUrl('/archive.file');

        $expected = 'hello word';

        $fetch = $this->createStub(Fetch::class);
        $fetch->method('get')->willReturn($expected);

        $downloader = new Downloader($fetch, $this->createConfigStub());
        $downloader->getArchive('GeoLite2-ASN', $file);

        $this->assertFileExists($file);
        $this->assertEquals($expected, file_get_contents($file));
    }

    /**
     * Test `getArchive()` failure
     */
    public function testGetArchiveFailure(): void
    {
        $this->expectOutputRegex('/Downloading database/');
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to download database file');

        $fetch = $this->createStub(Fetch::class);
        $fetch->method('get')->willThrowException(new FetchException('Request failed'));

        $downloader = new Downloader($fetch, $this->createConfigStub());
        $downloader->getArchive('GeoLite2-ASN', './backend/tests/files');
    }

    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getMaxMindLicenseKey')->willReturn('qwerty-qwerty');
        $config->method('getMaxMindDownloadUrl')->willReturn('https://example.com/?');
        return $config;
    }
}
