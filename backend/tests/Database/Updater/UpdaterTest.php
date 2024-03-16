<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Database\Updater\Updater;
use IntruderAlert\Exception\FetchException;
use IntruderAlert\Exception\AppException;

class UpdaterTest extends TestCase
{
    private static string $tempFolder = '';

    public static function setUpBeforeClass(): void
    {
        self::$tempFolder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'intruder-alert-tests';
    }

    public function setup(): void
    {
        mkdir(self::$tempFolder . DIRECTORY_SEPARATOR . 'geoip', recursive: true);
    }

    public function tearDown(): void
    {
        $this->removeDir(self::$tempFolder);
    }

    public function testUpdater(): void
    {
        $this->expectOutputRegex('/Updated Geoip2 database: GeoLite2-Country/');

        $geoIpFolder = self::$tempFolder . DIRECTORY_SEPARATOR . 'geoip';

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

        $updater = new Updater($config, $fetch, new Logger());
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
        $fetch = new Fetch($config->getUseragent());
        $updater = new Updater($config, $fetch, new Logger());
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

        $updater = new Updater($config, $fetch, new Logger());
        $updater->run();
    }

    /**
     * Remove directory and its contents
     *
     * @param string $path Directory path
     */
    private function removeDir($path): void
    {
        if (is_dir($path) === true) {
            $directory = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
            $items = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($items as $item) {
                if ($item->isDir() === true) {
                    rmdir($item);
                } else {
                    unlink($item);
                }
            }

            rmdir($path);
        }
    }
}
