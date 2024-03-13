<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Database\Updater\Updater;
use IntruderAlert\Exception\FetchException;
use IntruderAlert\Exception\AppException;

class UpdaterTest extends TestCase
{
    public function testWithNoMindLicenseKey(): void
    {
        $this->expectNotToPerformAssertions();

        $config = new Config();
        $fetch = new Fetch($config->getUseragent());
        $updater = new Updater($config, $fetch);
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

        $updater = new Updater($config, $fetch);
        $updater->run();
    }
}
