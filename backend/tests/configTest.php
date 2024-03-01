<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Exception\ConfigException;

class ConfigTest extends TestCase
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
        $this->assertEquals(constant('VERSION'), $config->getVersion());
    }

    /**
     * Test `getUseragent()`
     */
    public function testGetUseragent(): void
    {
        $useragent = sprintf(
            'Intruder Alert/%s (+https://github.com/VerifiedJoseph/intruder-alert)',
            constant('VERSION')
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
        putenv('DASH_DAEMON_LOG=true');

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
     * Test config with no `IA_LOG_FOLDER` or `IA_LOG_PATHS`
     */
    public function testNoLogPathsOrLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Environment variable IA_LOG_FOLDER or IA_LOG_PATHS must be set');

        $config = new Config();
        $config->checkCli();
    }

    /**
     * Test config with empty `IA_LOG_PATHS`
     */
    public function testEmptyLogPaths(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log paths variable can not be empty');

        putenv('IA_LOG_PATHS=');

        $config = new Config();
        $config->checkCli();
    }

    /**
     * Test config with empty `IA_LOG_FOLDER`
     */
    public function testEmptyLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log folder variable can not be empty');

        putenv('IA_LOG_FOLDER=');

        $config = new Config();
        $config->checkLogFolder();
    }

    /**
     * Test config with `IA_LOG_FOLDER` folder that does not exist
     */
    public function testDoesNotExistLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log folder does not exist');

        $folder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fail2ban-logs';

        putenv('IA_LOG_FOLDER=' . $folder);

        $config = new Config();
        $config->checkLogFolder();
    }

    /**
     * Test config with empty `IA_MAXMIND_LICENSE_KEY`
     */
    public function testEmptyMaxMindLicenseKey(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('MaxMind license key can not be empty');

        putenv('IA_MAXMIND_LICENSE_KEY=');

        $config = new Config();
        $config->checkMaxMindLicenseKey();
    }

    /**
     * Test config with empty `IA_ASN_DATABASE` path
     */
    public function testEmptyAsnDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 ASN database path must be set');

        putenv('IA_ASN_DATABASE=');

        $config = new Config();
        $config->checkDatabases();
    }

    /**
     * Test config with empty `IA_COUNTRY_DATABASE` path
     */
    public function testEmptyCountryDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 Country database path must be set');

        putenv('IA_ASN_DATABASE=fake.path');
        putenv('IA_COUNTRY_DATABASE=');

        $config = new Config();
        $config->checkDatabases();
    }

    /**
     * Test config with `IA_ASN_DATABASE` path that does not exist
     */
    public function testNotExistAsnDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 ASN database not found');

        putenv('IA_ASN_DATABASE=fake.path');
        putenv('IA_COUNTRY_DATABASE=fake.path');

        $config = new Config();
        $config->checkDatabases();
    }

    /**
     * Test config with `IA_COUNTRY_DATABASE` path that does not exist
     */
    public function testNotExistCountryDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 Country database not found');

        putenv('IA_ASN_DATABASE=backend/tests/files/fake-database.file');
        putenv('IA_COUNTRY_DATABASE=fake.path');

        $config = new Config();
        $config->checkDatabases();
    }

    /**
     * Test invalid GeoLite2 database
     */
    public function testInvalidDatabase(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 database is invalid: backend/tests/files/fake-database.file');

        putenv('IA_LOG_PATHS=fail2ban.log');
        putenv('IA_ASN_DATABASE=backend/tests/files/fake-database.file');
        putenv('IA_COUNTRY_DATABASE=backend/tests/files/fake-database.file');

        $config = new Config();
        $config->checkDatabases();
    }

    /**
     * Test `DASH_CHARTS` variable type
     */
    public function testDashChartVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Charts environment variable must be true or false');

        putenv('IA_DASH_CHARTS=string');

        $config = new Config();
        $config->checkDashboard();
    }

    /**
     * Test `IA_DASH_UPDATES` variable type
     */
    public function testDashUpdatesVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Dashboard updates environment variable must be true or false');

        putenv('IA_DASH_UPDATES=string');

        $config = new Config();
        $config->checkDashboard();
    }

    /**
     * Test `IA_DASH_DAEMON_LOG` variable type
     */
    public function testDashDaemonLogVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Dashboard daemon log environment variable must be true or false');

        putenv('IA_DASH_DAEMON_LOG=string');

        $config = new Config();
        $config->checkDashboard();
    }

    /**
     * Test no `IA_TIMEZONE` variable
     */
    public function testNoTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Timezone environment variable must be set');

        $config = new Config();
        $config->checkTimeZones();
    }

    /**
     * Test `IA_TIMEZONE` unknown timezone
     */
    public function testUnknownTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown timezone given');

        putenv('IA_TIMEZONE=Europe/Coventry');

        $config = new Config();
        $config->checkTimeZones();
    }

    /**
     * Test empty `IA_SYSTEM_LOG_TIMEZONE`
     */
    public function testEmptySystemLogTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Timezone can not be empty [IA_SYSTEM_LOG_TIMEZONE]');

        putenv('IA_TIMEZONE=Europe/London');
        putenv('IA_SYSTEM_LOG_TIMEZONE=');

        $config = new Config();
        $config->checkTimeZones();
    }

    /**
     * Test `IA_SYSTEM_LOG_TIMEZONE` with unknown timezone
     */
    public function testUnknownSystemLogTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown timezone given [IA_SYSTEM_LOG_TIMEZONE]');

        putenv('IA_TIMEZONE=Europe/London');
        putenv('IA_SYSTEM_LOG_TIMEZONE=Europe/Coventry');

        $config = new Config();
        $config->checkTimeZones();
    }

    /**
     * Test not setting `IA_SYSTEM_LOG_TIMEZONE`
     */
    public function testNotSettingSystemLogTimezone(): void
    {
        putenv('IA_TIMEZONE=Europe/London');

        $defaultTimezone = date_default_timezone_get();

        $config = new Config();
        $config->checkTimeZones();

        $this->assertEquals($defaultTimezone, $config->getSystemLogTimezone());
    }
}
