<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Version;

class ConfigTest extends TestCase
{
    /** @var array<string, mixed> $defaults */
    private static array $defaults = [];

    public static function setupBeforeClass(): void
    {
        $reflection = new ReflectionClass(new Config());
        self::$defaults = $reflection->getProperty('config')->getValue(new Config());
    }

    /**
     * Test `setDir()`
     */
    public function testSetDir(): void
    {
        $dir = 'backend/tests';

        $config = new Config();
        $config->setDir($dir);

        $this->assertEquals($dir . DIRECTORY_SEPARATOR, $config->getPath());
    }

    /**
     * Test `getPath()`
     */
    public function testGetPath(): void
    {
        $dir = 'backend/tests';
        $filepath = $dir . DIRECTORY_SEPARATOR . 'fake.file';

        $config = new Config();
        $config->setDir($dir);

        $this->assertEquals($filepath, $config->getPath('fake.file'));
    }

    /**
     * Test `getVersion()`
     */
    public function testGetVersion(): void
    {
        $config = new Config();
        $this->assertEquals(Version::get(), $config->getVersion());
    }

    /**
     * Test `getUseragent()`
     */
    public function testGetUseragent(): void
    {
        $useragent = sprintf(
            'Intruder Alert/%s (+https://github.com/VerifiedJoseph/intruder-alert)',
            Version::get()
        );

        $config = new Config();
        $this->assertEquals($useragent, $config->getUseragent());
    }

    /**
     * Test `getChartsStatus()`
     */
    public function testGetChartsStatus(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['dash_charts'], $config->getChartsStatus());
    }

    /**
     * Test `getDashUpdatesStatus()`
     */
    public function testGetDashUpdatesStatus(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['dash_updates'], $config->getDashUpdatesStatus());
    }

    /**
     * Test `getDashDaemonLogStatus()`
     */
    public function testGetDashDaemonLogStatus(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['dash_daemon_log'], $config->getDashDaemonLogStatus());
    }

    /**
     * Test `getLogFolder()`
     */
    public function testGetLogFolders(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['log_folder'], $config->getLogFolder());
    }

    /**
     * Test `getLogPaths()`
     */
    public function testGetLogPaths(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['log_paths'], $config->getLogPaths());
    }

    /**
     * Test `getMaxMindLicenseKey()`
     */
    public function testGetMaxMindLicenseKey(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['maxmind_license_key'], $config->getMaxMindLicenseKey());
    }

    /**
     * Test `getMaxMindDownloadUrl()`
     */
    public function testGetMaxMindDownloadUrl(): void
    {
        $config = new Config();
        $this->assertMatchesRegularExpression(
            '/https:\/\/download.maxmind.com/',
            $config->getMaxMindDownloadUrl()
        );
    }

    /**
     * Test `getGeoIpDatabaseFolder()`
     */
    public function testGetGeoIpDatabaseFolder(): void
    {
        $config = new Config();
        $this->assertEquals('data/geoip2', $config->getGeoIpDatabaseFolder());
    }

    /**
     * Test `getAsnDatabasePath()` when `IA_ASN_DATABASE` is passed
     */
    public function testGetAsnDatabasePath(): void
    {
        $config = new Config();
        $this->assertEquals(
            self::$defaults['asn_database_path'],
            $config->getAsnDatabasePath()
        );
    }

    /**
     * Test `getCountryDatabasePath()` when `IA_COUNTRY_DATABASE is passed
     */
    public function testGetCountryDatabasePath(): void
    {
        $config = new Config();
        $this->assertEquals(
            self::$defaults['country_database_path'],
            $config->getCountryDatabasePath()
        );
    }

    /**
     * Test `getAsnDatabasePath()` when `IA_MAXMIND_LICENSE_KEY` is passed
     */
    public function testGetAsnDatabasePathWhenMaxmindKeyPassed(): void
    {
        $config = new Config();

        $reflection = new ReflectionClass($config);
        $property = $reflection->getProperty('config');
        $property->setAccessible(true);
        $property->setValue($config, ['maxmind_license_key' => 'qwerty']);

        $this->assertEquals(
            'data/geoip2/GeoLite2-ASN.mmdb',
            $config->getAsnDatabasePath()
        );
    }

    /**
     * Test `getCountryDatabasePath()` when `IA_MAXMIND_LICENSE_KEY is passed
     */
    public function testGetCountryDatabasePathWhenMaxmindKeyPassed(): void
    {
        $config = new Config();

        $reflection = new ReflectionClass($config);
        $property = $reflection->getProperty('config');
        $property->setAccessible(true);
        $property->setValue($config, ['maxmind_license_key' => 'qwerty']);

        $this->assertEquals(
            'data/geoip2/GeoLite2-Country.mmdb',
            $config->getCountryDatabasePath()
        );
    }

    /**
     * Test `getTimezone()`
     */
    public function testGetTimezone(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['timezone'], $config->getTimezone());
    }

    /**
     * Test `getSystemLogTimezone()`
     */
    public function testGetSystemLogTimezone(): void
    {
        $config = new Config();
        $this->assertEquals(self::$defaults['log_timezone'], $config->getSystemLogTimezone());
    }

    /**
     * Test `getCacheFilePath()`
     */
    public function testGetCacheFilePath(): void
    {
        $dir = 'backend';
        $expected = $dir . DIRECTORY_SEPARATOR . 'data/cache.json';

        $config = new Config();
        $config->setDir($dir);
        $this->assertEquals($expected, $config->getCacheFilePath());
    }

    /**
     * Test `getDataFilePath()`
     */
    public function testGetDataFilePath(): void
    {
        $dir = 'backend';
        $expected = $dir . DIRECTORY_SEPARATOR . 'data/data.json';

        $config = new Config();
        $config->setDir($dir);
        $this->assertEquals($expected, $config->getDataFilePath());
    }
}
