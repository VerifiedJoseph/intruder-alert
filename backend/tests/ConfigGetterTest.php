<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Version;

class ConfigGetterTest extends TestCase
{
    public function setUp(): void
    {
        // Unset environment variables before each test
        putenv('IA_LOG_PATHS');
        putenv('IA_LOG_FOLDER');
        putenv('IA_MAXMIND_LICENSE_KEY');
        putenv('IA_ASN_DATABASE');
        putenv('IA_COUNTRY_DATABASE');
        putenv('IA_TIMEZONE');
        putenv('IA_SYSTEM_LOG_TIMEZONE');
        putenv('IA_DASH_CHARTS');
        putenv('IA_DASH_UPDATES');
        putenv('IA_DASH_DAEMON_LOG');
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
        putenv('IA_DASH_CHARTS=true');

        $config = new Config();
        $config->checkDashboard();

        $this->assertTrue($config->getChartsStatus());
    }

    /**
     * Test `getDashUpdatesStatus()`
     */
    public function testGetDashUpdatesStatus(): void
    {
        putenv('IA_DASH_UPDATES=true');

        $config = new Config();
        $config->checkDashboard();

        $this->assertTrue($config->getDashUpdatesStatus());
    }

    /**
     * Test `getDashDaemonLogStatus()`
     */
    public function testGetDashDaemonLogStatus(): void
    {
        putenv('IA_DASH_DAEMON_LOG=true');

        $config = new Config();
        $config->checkDashboard();

        $this->assertTrue($config->getDashDaemonLogStatus());
    }

    /**
     * Test `getLogFolder()`
     */
    public function testGetLogFolders(): void
    {
        putenv('IA_LOG_FOLDER=backend/tests');

        $config = new Config();
        $config->checkLogFolder();

        $this->assertEquals('backend/tests', $config->getLogFolder());
    }

    /**
     * Test `getLogPaths()`
     */
    public function testGetLogPaths(): void
    {
        $paths = 'example/fail2ban.log;example/fail2ban.log.1';

        putenv('IA_LOG_PATHS=' . $paths);

        $config = new Config();
        $config->checkLogPaths();

        $this->assertEquals($paths, $config->getLogPaths());
    }

    /**
     * Test `getMaxMindLicenseKey()`
     */
    public function testGetMaxMindLicenseKey(): void
    {
        putenv('IA_MAXMIND_LICENSE_KEY=fake-key');

        $config = new Config();
        $config->checkMaxMindLicenseKey();

        $this->assertEquals('fake-key', $config->getMaxMindLicenseKey());
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
        putenv('IA_ASN_DATABASE=backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        putenv('IA_COUNTRY_DATABASE=backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');

        $config = new Config();
        $config->checkDatabases();

        $this->assertEquals(
            'backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb',
            $config->getAsnDatabasePath()
        );
    }

    /**
     * Test `getCountryDatabasePath()` when `IA_COUNTRY_DATABASE is passed
     */
    public function testGetCountryDatabasePath(): void
    {
        putenv('IA_ASN_DATABASE=backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        putenv('IA_COUNTRY_DATABASE=backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');

        $config = new Config();
        $config->checkDatabases();

        $this->assertEquals(
            'backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb',
            $config->getCountryDatabasePath()
        );
    }

    /**
     * Test `getAsnDatabasePath()` when `IA_MAXMIND_LICENSE_KEY` is passed
     */
    public function testGetAsnDatabasePathWhenMaxmindKeyPassed(): void
    {
        putenv('IA_MAXMIND_LICENSE_KEY=fake-key');

        $config = new Config();
        $config->checkMaxMindLicenseKey();

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
        putenv('IA_MAXMIND_LICENSE_KEY=fake-key');

        $config = new Config();
        $config->checkMaxMindLicenseKey();

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
        putenv('IA_TIMEZONE=Europe/London');

        $config = new Config();
        $config->checkTimeZones();

        $this->assertEquals('Europe/London', $config->getTimezone());
    }

    /**
     * Test `getSystemLogTimezone()`
     */
    public function testGetSystemLogTimezone(): void
    {
        putenv('IA_TIMEZONE=Europe/London');
        putenv('IA_SYSTEM_LOG_TIMEZONE=UTC');

        $config = new Config();
        $config->checkTimeZones();

        $this->assertEquals('UTC', $config->getSystemLogTimezone());
    }
}
