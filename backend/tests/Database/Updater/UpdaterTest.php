<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Database\Updater\Updater;
use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Exception\FetchException;
use IntruderAlert\Exception\AppException;

#[CoversClass(Updater::class)]
#[UsesClass(Config::class)]
#[UsesClass(Fetch::class)]
#[UsesClass(Logger::class)]
#[UsesClass(FetchException::class)]
#[UsesClass(AppException::class)]
#[UsesClass(IntruderAlert\Database\Updater\Url::class)]
#[UsesClass(IntruderAlert\Database\Updater\Downloader::class)]
#[UsesClass(IntruderAlert\Database\Updater\Extractor::class)]
#[UsesClass(IntruderAlert\Database\Updater\TimestampFile::class)]
#[UsesClass(IntruderAlert\Config\Check::class)]
#[UsesClass(IntruderAlert\Helper\File::class)]
#[UsesClass(IntruderAlert\Helper\Output::class)]
#[UsesClass(IntruderAlert\Version::class)]
class UpdaterTest extends AbstractTestCase
{
    public function setup(): void
    {
        mkdir(self::$tempPath . 'geoip', recursive: true);
    }

    public function tearDown(): void
    {
        $this->removeDir(self::$tempPath);
    }

    public function testUpdater(): void
    {
        $this->expectOutputRegex('/Updated Geoip2 database: GeoLite2-Country/');

        $geoIpFolder = self::$tempPath . 'geoip';

        $config = $this->createStub(Config::class);
        $config->method('getMaxMindLicenseKey')->willReturn('qwerty-qwerty');
        $config->method('getMaxMindDownloadUrl')->willReturn('https://example.invalid/?');
        $config->method('getGeoIpDatabaseFolder')->willReturn($geoIpFolder);
        $config->method('getAsnDatabasePath')->willReturn($geoIpFolder . '/GeoLite2-ASN.mmdb');
        $config->method('getCountryDatabasePath')->willReturn($geoIpFolder . '/GeoLite2-Country.mmdb');

        $fetch = $this->createStub(Fetch::class);
        $fetch->method('get')->willReturn(
            file_get_contents('backend/tests/files/checksum/GeoLite2-ASN.checksum'),
            file_get_contents('backend/tests/files/tar/has-mmdb/GeoLite2-ASN_19700101.tar.gz'),
            file_get_contents('backend/tests/files/checksum/GeoLite2-Country.checksum'),
            file_get_contents('backend/tests/files/tar/has-mmdb/GeoLite2-Country_19700101.tar.gz'),
        );

        $updater = new Updater($config, $fetch, self::$logger);
        $updater->run();

        $this->assertFileExists($geoIpFolder . '/GeoLite2-ASN.mmdb');
        $this->assertFileExists($geoIpFolder . '/GeoLite2-Country.mmdb');
        $this->assertFileExists($geoIpFolder . '/GeoLite2-ASN.timestamp');
        $this->assertFileExists($geoIpFolder . '/GeoLite2-Country.timestamp');
        $this->assertFileDoesNotExist($geoIpFolder . '/GeoLite2-ASN_19700101');
        $this->assertFileDoesNotExist($geoIpFolder . '/GeoLite2-Country_19700101');
        $this->assertFileDoesNotExist($geoIpFolder . '/GeoLite2-ASN_19700101.tar.gz');
        $this->assertFileDoesNotExist($geoIpFolder . '/GeoLite2-Country_19700101.tar.gz');
    }

    public function testNoMindLicenseKey(): void
    {
        $this->expectNotToPerformAssertions();

        $config = new Config();
        $fetch = new Fetch($config->getUseragent(), self::$logger);
        $updater = new Updater($config, $fetch, self::$logger);
        $updater->run();
    }

    public function testException(): void
    {
        $this->expectOutputRegex('/Updating Geoip2 database/');
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Geoip2 database update failed. Failed to download checksum file');

        $config = $this->createStub(Config::class);
        $config->method('getMaxMindLicenseKey')->willReturn('qwerty-qwerty');
        $config->method('getAsnDatabasePath')->willReturn('data/geoip2/GeoLite2-ASN.mmdb');
        $config->method('getCountryDatabasePath')->willReturn('data/geoip2/GeoLite2-Country.mmdb');

        $fetch = $this->createStub(Fetch::class);
        $fetch->method('get')->willThrowException(new FetchException('Request failed'));

        $updater = new Updater($config, $fetch, self::$logger);
        $updater->run();
    }
}
