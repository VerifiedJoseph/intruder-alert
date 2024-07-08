<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Database\Updater\Downloader;
use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Exception\FetchException;

#[CoversClass(Downloader::class)]
#[UsesClass(Config::class)]
#[UsesClass(Fetch::class)]
#[UsesClass(Logger::class)]
#[UsesClass(FetchException::class)]
#[UsesClass(IntruderAlert\Database\Updater\Url::class)]
#[UsesClass(IntruderAlert\Helper\File::class)]
#[UsesClass(IntruderAlert\Helper\Output::class)]
class DownloaderTest extends AbstractTestCase
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

        $downloader = new Downloader($fetch, $this->createConfigStub(), new Logger());
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

        $downloader = new Downloader($fetch, $this->createConfigStub(), new Logger());
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

        $downloader = new Downloader($fetch, $this->createConfigStub(), new Logger());
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

        $downloader = new Downloader($fetch, $this->createConfigStub(), new Logger());
        $downloader->getArchive('GeoLite2-ASN', './backend/tests/files');
    }

    /**
     * Test checkArchiveIntegrity()
     */
    public function testCheckArchiveIntegrity(): void
    {
        $this->expectNotToPerformAssertions();

        $file = mockfs::getUrl('/test.file');
        file_put_contents($file, uniqid());
        $checksum = (string) hash_file('sha256', $file);

        $downloader = new Downloader($this->createStub(Fetch::class), $this->createConfigStub(), new Logger());
        $downloader->checkArchiveIntegrity($checksum, mockfs::getUrl('/test.file'));
    }

    /**
     * Test checkArchiveIntegrity() failure
     */
    public function testCheckArchiveIntegrityFailure(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Integrity check failed');

        $file = mockfs::getUrl('/test.file');
        file_put_contents($file, uniqid());
        $checksum = (string) hash_file('sha1', $file);

        $downloader = new Downloader(new Fetch('qwerty-useragent'), $this->createConfigStub(), new Logger());
        $downloader->checkArchiveIntegrity($checksum, mockfs::getUrl('/test.file'));
    }

    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getMaxMindLicenseKey')->willReturn('qwerty-qwerty');
        $config->method('getMaxMindDownloadUrl')->willReturn('https://example.com/?');
        return $config;
    }
}
