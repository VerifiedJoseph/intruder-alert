<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\WithEnvironmentVariable;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Config;
use IntruderAlert\Config\Check;
use IntruderAlert\Exception\ConfigException;

#[CoversClass(Check::class)]
#[UsesClass(Config::class)]
#[UsesClass(ConfigException::class)]
#[UsesClass(IntruderAlert\Config\AbstractConfig::class)]
class CheckTest extends AbstractTestCase
{
    /** @var array<string, mixed> $defaults */
    private static array $defaults = [];

    public static function setupBeforeClass(): void
    {
        $reflection = new ReflectionClass(new Config());
        self::$defaults = $reflection->getProperty('config')->getValue(new Config());
    }

    public function tearDown(): void
    {
        stream_context_set_default(
            [
                'mfs' => [
                    'mkdir_fail' => false,
                ]
            ]
        );
    }

    /**
     * Test `getConfig`
     */
    public function testGetConfig(): void
    {
        $check = new Check(self::$defaults);
        $this->assertEquals(self::$defaults, $check->getConfig());
    }

    /**
     * Test `version()`
     */
    public function testVersion(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Intruder Alert requires at least PHP version 8.3.0');

        $check = new Check(self::$defaults);
        $check->version('8.2.0', '8.3.0');
    }

    /**
     * Test `extensions()`
     */
    public function testExtensions(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('PHP extension error: pgp extension not loaded');

        $check = new Check(self::$defaults);
        $check->extensions(['pgp']);
    }

    /**
     * Test `IA_LOG_FOLDER`
     */
    #[WithEnvironmentVariable('IA_LOG_FOLDER', 'backend/tests/files/logs')]
    public function testLogFolder(): void
    {
        $check = new Check(self::$defaults);
        $check->logFolder();
        $config = $check->getConfig();

        $this->assertEquals('backend/tests/files/logs', $config['log_folder']);
    }

    /**
     * Test config with empty `IA_LOG_FOLDER`
     */
    #[WithEnvironmentVariable('IA_LOG_FOLDER', '')]
    public function testEmptyLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log folder variable can not be empty');

        $check = new Check(self::$defaults);
        $check->logFolder();
    }

    /**
     * Test config with `IA_LOG_FOLDER` folder that does not exist
     */
    #[WithEnvironmentVariable('IA_LOG_FOLDER', 'tests/fail2ban-logs')]
    public function testDoesNotExistLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log folder does not exist');

        $check = new Check(self::$defaults);
        $check->logFolder();
    }

    /**
     * Test `IA_LOG_PATHS`
     */
    #[WithEnvironmentVariable('IA_LOG_PATHS', 'backend/tests/files/logs/has-bans/fail2ban.log')]
    public function testLogPaths(): void
    {
        $check = new Check(self::$defaults);
        $check->logPaths();
        $config = $check->getConfig();

        $this->assertEquals('backend/tests/files/logs/has-bans/fail2ban.log', $config['log_paths']);
    }

    /**
     * Test empty `IA_LOG_PATHS`
     */
    #[WithEnvironmentVariable('IA_LOG_PATHS', '')]
    public function testEmptyLogPaths(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log paths variable can not be empty');

        $check = new Check(self::$defaults);
        $check->logPaths();
    }

    /**
     * Test `IA_MAXMIND_LICENSE_KEY`
     */
    #[WithEnvironmentVariable('IA_MAXMIND_LICENSE_KEY', 'qwerty')]
    public function testMaxMindLicenseKey(): void
    {
        $check = new Check(self::$defaults);
        $check->maxMindLicenseKey();
        $config = $check->getConfig();

        $this->assertEquals('qwerty', $config['maxmind_license_key']);
    }

    /**
     * Test empty `IA_MAXMIND_LICENSE_KEY`
     */
    #[WithEnvironmentVariable('IA_MAXMIND_LICENSE_KEY', '')]
    public function testEmptyMaxMindLicenseKey(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('MaxMind license key can not be empty');

        $check = new Check(self::$defaults);
        $check->maxMindLicenseKey();
    }

    /**
     * Test `IA_ASN_DATABASE` and `IA_COUNTRY_DATABASE`
     */
    public function testDatabasePaths(): void
    {
        $asn = 'backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb';
        $country = 'backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb';

        putenv('IA_ASN_DATABASE=' . $asn);
        putenv('IA_COUNTRY_DATABASE=' . $country);

        $check = new Check(self::$defaults);
        $check->databases();
        $config = $check->getConfig();

        $this->assertEquals($asn, $config['asn_database_path']);
        $this->assertEquals($country, $config['country_database_path']);

        putenv('IA_ASN_DATABASE');
        putenv('IA_COUNTRY_DATABASE');
    }

    /**
     * Test empty `IA_ASN_DATABASE`
     */
    #[WithEnvironmentVariable('IA_ASN_DATABASE')]
    public function testEmptyAsnDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 ASN database path must be set');

        $check = new Check(self::$defaults);
        $check->databases();
    }

    /**
     * Test empty `IA_COUNTRY_DATABASE`
     */
    public function testEmptyCountryDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 Country database path must be set');

        putenv('IA_ASN_DATABASE=backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        putenv('IA_COUNTRY_DATABASE=');

        $check = new Check(self::$defaults);
        $check->databases();

        putenv('IA_ASN_DATABASE');
        putenv('IA_COUNTRY_DATABASE');
    }

    /**
     * Test `IA_ASN_DATABASE` with path that does not exist
     */
    public function testNotExistAsnDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 ASN database not found');

        putenv('IA_ASN_DATABASE=fake.path');
        putenv('IA_COUNTRY_DATABASE=backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');

        $check = new Check(self::$defaults);
        $check->databases();

        putenv('IA_ASN_DATABASE');
        putenv('IA_COUNTRY_DATABASE');
    }

    /**
     * Test `IA_COUNTRY_DATABASE` with path that does not exist
     */
    public function testNotExistCountryDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 Country database not found');

        putenv('IA_ASN_DATABASE=backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        putenv('IA_COUNTRY_DATABASE=fake.path');

        $check = new Check(self::$defaults);
        $check->databases();

        putenv('IA_ASN_DATABASE');
        putenv('IA_COUNTRY_DATABASE');
    }

    /**
     * Test `isDatabaseValid()`
     */
    public function testIsDatabaseValid(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 database is invalid: backend/tests/files/fake-database.file');

        $check = new Check(self::$defaults);
        $check->isDatabaseValid('backend/tests/files/fake-database.file');
    }

    /**
     * Test `IA_DASH_CHARTS`
     */
    #[WithEnvironmentVariable('IA_DASH_CHARTS', 'false')]
    public function testDashCharts(): void
    {
        $check = new Check(self::$defaults);
        $check->dashboardCharts();
        $config = $check->getConfig();

        $this->assertFalse($config['dash_charts']);
    }

    /**
     * Test `IA_DASH_CHARTS` variable type
     */
    #[WithEnvironmentVariable('IA_DASH_CHARTS', 'string')]
    public function testDashChartVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Charts environment variable must be true or false');

        $check = new Check(self::$defaults);
        $check->dashboardCharts();
    }

    /**
     * Test `IA_DASH_UPDATES`
     */
    #[WithEnvironmentVariable('IA_DASH_UPDATES', 'false')]
    public function testDashUpdates(): void
    {
        $check = new Check(self::$defaults);
        $check->dashboardUpdates();
        $config = $check->getConfig();

        $this->assertFalse($config['dash_updates']);
    }

    /**
     * Test `IA_DASH_UPDATES` variable type
     */
    #[WithEnvironmentVariable('IA_DASH_UPDATES', 'string')]
    public function testDashUpdatesVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Dashboard updates environment variable must be true or false');

        $check = new Check(self::$defaults);
        $check->dashboardUpdates();
    }

    /**
     * Test `IA_DASH_DAEMON_LOG`
     */
    #[WithEnvironmentVariable('IA_DASH_DAEMON_LOG', 'false')]
    public function testDashDaemonLog(): void
    {
        $check = new Check(self::$defaults);
        $check->dashboardDaemonLog();
        $config = $check->getConfig();

        $this->assertFalse($config['dash_daemon_log']);
    }

    /**
     * Test `IA_DASH_DAEMON_LOG` variable type
     */
    #[WithEnvironmentVariable('IA_DASH_DAEMON_LOG', 'string')]
    public function testDashDaemonLogVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Dashboard daemon log environment variable must be true or false');

        $check = new Check(self::$defaults);
        $check->dashboardDaemonLog();
    }

    /**
     * Test `dashboardDefaultChart()` with valid `IA_DASH_DEFAULT_CHART` value
     */
    #[WithEnvironmentVariable('IA_DASH_DEFAULT_CHART', '24hours')]
    public function testDashboardDefaultChart(): void
    {
        $check = new Check(self::$defaults);
        $check->dashboardDefaultChart(['last24hours', 'last48hours']);
        $config = $check->getConfig();

        $this->assertEquals('last24hours', $config['dash_default_chart']);
    }

    /**
     * Test `dashboardDefaultChart()` with unsupported `IA_DASH_DEFAULT_CHART` value
     */
    #[WithEnvironmentVariable('IA_DASH_DEFAULT_CHART', 'string')]
    public function testDashboardDefaultChartUnsupported(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unsupported value for dashboard default chart environment variable');

        $check = new Check(self::$defaults);
        $check->dashboardDefaultChart(['last24hours', 'last48hours']);
    }

    /**
     * Test `dashboardPage()` with valid `IA_DASH_PAGE_SIZE` value
     */
    #[WithEnvironmentVariable('IA_DASH_PAGE_SIZE', '25')]
    public function testDashboardPageSize(): void
    {
        $check = new Check(self::$defaults);
        $check->dashboardPageSize([25, 50]);
        $config = $check->getConfig();

        $this->assertEquals(25, $config['dash_page_size']);
    }

    /**
     * Test `dashboardPage()` with unsupported `IA_DASH_PAGE_SIZE` value
     */
    #[WithEnvironmentVariable('IA_DASH_PAGE_SIZE', '10')]
    public function testDashboardPageSizeUnsupported(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unsupported value for dashboard table page size environment variable');

        $check = new Check(self::$defaults);
        $check->dashboardPageSize([25, 50]);
    }

    /**
     * Test `IA_TIMEZONE` variable
     */
    #[WithEnvironmentVariable('IA_TIMEZONE', 'Europe/London')]
    public function testTimezone(): void
    {
        $check = new Check(self::$defaults);
        $check->timezone();
        $config = $check->getConfig();

        $this->assertEquals('Europe/London', $config['timezone']);
    }

    /**
     * Test no `IA_TIMEZONE` variable
     */
    public function testNoTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Timezone environment variable must be set');

        $check = new Check(self::$defaults);
        $check->timezone();
    }

    /**
     * Test `IA_TIMEZONE` unknown timezone
     */
    #[WithEnvironmentVariable('IA_TIMEZONE', 'Europe/Coventry')]
    public function testUnknownTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown timezone given');

        $check = new Check(self::$defaults);
        $check->timezone();
    }

    /**
     * Test `IA_SYSTEM_LOG_TIMEZONE`
     */
    #[WithEnvironmentVariable('IA_SYSTEM_LOG_TIMEZONE', 'UTC')]
    public function testSystemLogTimezone(): void
    {
        $check = new Check(self::$defaults);
        $check->systemLogTimezone();
        $config = $check->getConfig();

        $this->assertEquals('UTC', $config['log_timezone']);
    }

    /**
     * Test empty `IA_SYSTEM_LOG_TIMEZONE`
     */
    #[WithEnvironmentVariable('IA_SYSTEM_LOG_TIMEZONE', '')]
    public function testEmptySystemLogTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Timezone can not be empty [IA_SYSTEM_LOG_TIMEZONE]');

        $check = new Check(self::$defaults);
        $check->systemLogTimezone();
    }

    /**
     * Test `IA_SYSTEM_LOG_TIMEZONE` with unknown timezone
     */
    #[WithEnvironmentVariable('IA_SYSTEM_LOG_TIMEZONE', 'Europe/Coventry')]
    public function testUnknownSystemLogTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown timezone given [IA_SYSTEM_LOG_TIMEZONE]');

        $check = new Check(self::$defaults);
        $check->systemLogTimezone();
    }

    /**
     * Test not setting `IA_SYSTEM_LOG_TIMEZONE`
     */
    public function testNotSettingSystemLogTimezone(): void
    {
        $tz = date_default_timezone_get();

        $check = new Check(self::$defaults);
        $check->systemLogTimezone();
        $config = $check->getConfig();

        $this->assertEquals($tz, $config['log_timezone']);
    }

    /**
     * Test not setting `IA_SYSTEM_LOG_TIMEZONE` when running in a docker container
     */
    #[WithEnvironmentVariable('IA_DOCKER', 'true')]
    public function testNotSettingSystemLogTimezoneDocker(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Fail2ban log timezone is required when running in a docker container');

        $check = new Check(self::$defaults);
        $check->systemLogTimezone();
    }

    /**
     * Test setting `IA_VERBOSE`
     */
    #[WithEnvironmentVariable('IA_VERBOSE', 'true')]
    public function testVerbose(): void
    {
        $check = new Check(self::$defaults);
        $check->verbose();

        $config = $check->getConfig();

        $this->assertTrue($config['verbose']);
    }

    /**
     * Test setting `IA_VERBOSE` is non-boolean value
     */
    #[WithEnvironmentVariable('IA_VERBOSE', 'string')]
    public function testVerboseNotBoolean(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Verbose logging environment variable must be true or false');

        $check = new Check(self::$defaults);
        $check->verbose();
    }

    /**
     * Test `folder()` folder creation failure
     */
    public function testFolderCreationFailure(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Failed to create folder');

        mockfs::create();
        $folder = mockfs::getUrl('/data');

        stream_context_set_default(
            [
                'mfs' => [
                    'mkdir_fail' => true,
                ]
            ]
        );

        $check = new Check(self::$defaults);
        $check->folder($folder);
    }
}
